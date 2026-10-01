<?php

namespace App\Services;

use App\Ht_result;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BetsApiService
{
    /**
     * Descarga y sincroniza los resultados finalizados de eSoccer Battle
     *
     * @param string|null $date Fecha en formato 'Y-m-d'. Si es null, usa la fecha actual.
     * @return int Cantidad de registros nuevos insertados.
     */
    public static function syncEndedEvents($date = null)
    {
        $token = '179257-cJnW9bS4XVntKF';
        $leagueId = '22614'; // ID correspondiente a eSoccer Battle 8 mins play
        
        // La API de BetsAPI usa el formato YYYYMMDD para el parámetro day
        $day = $date ? date('Ymd', strtotime($date)) : date('Ymd');
        
        $page = 1;
        $hasMore = true;
        $insertedCount = 0;

        while ($hasMore) {
            $url = "https://api.b365api.com/v1/events/ended?sport_id=1&league_id={$leagueId}&token={$token}&day={$day}&page={$page}";

            $response = self::makeCurlRequest($url);

            if (!$response) {
                break; // Error de conexión
            }

            $data = json_decode($response, true);
            
            // Si no hay resultados válidos, detener el bucle
            if (!isset($data['results']) || empty($data['results'])) {
                $hasMore = false;
                break;
            }

            foreach ($data['results'] as $match) {
                // 1. Filtrar solo partidos terminados con marcador final válido
                if ($match['time_status'] != 3 || !isset($match['ss'])) {
                    continue;
                }

                // Extracción FT (Full Time)
                $ftParts = explode('-', $match['ss']);
                if (count($ftParts) !== 2) continue;
                $ft1 = trim($ftParts[0]);
                $ft2 = trim($ftParts[1]);

                // Extracción de Equipo y Jugador 1 (Local)
                $homeRaw = $match['home']['name'];
                $partsHome = explode('(', $homeRaw);
                $team1 = isset($partsHome[0]) ? trim($partsHome[0]) : null;
                $player1Name = isset($partsHome[1]) ? strtoupper(trim(str_replace(')', '', $partsHome[1]))) : strtoupper(trim($homeRaw));

                // Extracción de Equipo y Jugador 2 (Visita)
                $awayRaw = $match['away']['name'];
                $partsAway = explode('(', $awayRaw);
                $team2 = isset($partsAway[0]) ? trim($partsAway[0]) : null;
                $player2Name = isset($partsAway[1]) ? strtoupper(trim(str_replace(')', '', $partsAway[1]))) : strtoupper(trim($awayRaw));

                // Conversión de timestamp (UTC) a la zona horaria utilizada en tu sistema
                $fechaCarbon = Carbon::createFromTimestamp($match['time'])->setTimezone('America/Santiago');
                $fecha = $fechaCarbon->format('Y-m-d');
                $hora  = $fechaCarbon->format('H:i:s');

                // 2. Validación contra nuestra base de datos si el partido existe
                $existe = Ht_result::where('fecha', $fecha)
                    ->where('hora', $hora)
                    ->where('player1', $player1Name)
                    ->where('player2', $player2Name)
                    ->first();

                // 3. Si el partido no existe, continuamos con el proceso
                if (!$existe) {
                    
                    // Consultar endpoint 'view' para traer el score del primer tiempo
                    $htData = self::getHalfTimeScore($match['id'], $token);
                    $ht1 = $htData['ht1'];
                    $ht2 = $htData['ht2'];

                    $spread = ($ft1 + $ft2) - ($ht1 + $ht2);

                    // Obtener o crear IDs de los jugadores
                    $p1_id = self::getOrCreatePlayer($player1Name);
                    $p2_id = self::getOrCreatePlayer($player2Name);

                    // 4. Se genera el arreglo
                    $results = [
                        'fecha'   => $fecha,
                        'hora'    => $hora,
                        'player1' => $player1Name,
                        'p1'      => $p1_id,
                        'ht1'     => $ht1,
                        'ft1'     => $ft1,
                        'player2' => $player2Name,
                        'p2'      => $p2_id,
                        'ht2'     => $ht2,
                        'ft2'     => $ft2,
                        'team1'   => $team1,
                        'team2'   => $team2,
                        'spread'  => $spread
                    ];


                    // 5. Se guarda
                    Ht_result::create($results);
                    $insertedCount++;
                }
            }

            // Manejo de la paginación de BetsAPI
            $pager = $data['pager'] ?? null;
            if ($pager && isset($pager['page'], $pager['total'], $pager['per_page'])) {
                if (($pager['page'] * $pager['per_page']) >= $pager['total']) {
                    $hasMore = false;
                } else {
                    $page++;
                }
            } else {
                $hasMore = false;
            }
        }

        return $insertedCount;
    }

    /**
     * Consulta el endpoint event/view para extraer el Half Time (HT)
     */
    private static function getHalfTimeScore($eventId, $token)
    {
        $url = "https://api.b365api.com/v1/event/view?token={$token}&event_id={$eventId}";
        $response = self::makeCurlRequest($url);
        
        $ht1 = 0;
        $ht2 = 0;

        if ($response) {
            $data = json_decode($response, true);
            // El score del HT viene en el índice '1' del arreglo de scores
            if (isset($data['results'][0]['scores']['1'])) {
                $ht1 = $data['results'][0]['scores']['1']['home'] ?? 0;
                $ht2 = $data['results'][0]['scores']['1']['away'] ?? 0;
            }
        }

        return ['ht1' => $ht1, 'ht2' => $ht2];
    }

    /**
     * Helper para ejecutar peticiones cURL limpiamente
     */
    private static function makeCurlRequest($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Retorna el ID del jugador, creándolo si no existe (Equivalente a la lógica en import_bets)
     */
    private static function getOrCreatePlayer($name)
    {
        $player = DB::table('players')->select('id')->where('name', 'like', '%' . $name . '%')->first();
        
        if ($player) {
            return $player->id;
        }

        DB::table('players')->insert(['name' => $name]);
        return DB::table('players')->select('id')->where('name', 'like', '%' . $name . '%')->first()->id;
    }
}