<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VaritiesImport;
use App\Colors;
use App\Bets;
use App\Bets_temp;
use App\Player;
use App\Ht_result;
use App\Models\Varity;
use DB;
use Carbon\Carbon;
use App\Services\BetsApiService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('import_bets');;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $colors = Colors::all();

      return view('home', compact('colors'));
    }

    /**
     * Función para cargar el excel 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function import()
    {
        return view('imports.import');

        $sigfigHashLength = [0, 5, 7, 8, 11, 12, 13, 15, 16, 17, 18];
        $BASE32_CODES = '0123456789bcdefghjkmnpqrstuvwxyz';
        $latitude  = -41.4689167;
        $longitude = -72.941135;
        $numberOfChars = 9;

        $chars = []; $bits = 0; $bitsTotal = 0; $hashValue = 0;
        $maxLat = 90; 
        $minLat = -90; 
        $maxLon = 180; $minLon = -180; $mid = 0.0;

         while (count($chars) < $numberOfChars) {
          if ($bitsTotal % 2 == 0) {
            $mid = ($maxLon + $minLon) / 2;
            if ($longitude > $mid) {
              $hashValue = ($hashValue << 1) + 1; #<<
              $minLon = $mid;
            } else {
              $hashValue = ($hashValue << 1) + 0; #<<
              $maxLon = $mid;
            }
          } else {
            $mid = ($maxLat + $minLat) / 2;
            if ($latitude > $mid) {
              $hashValue = ($hashValue << 1) + 1;#<<
              $minLat = $mid;
            } else {
              $hashValue = ($hashValue << 1) + 0;#<<
              $maxLat = $mid;
            }
          }

          $bits++;
          $bitsTotal++;
          if ($bits == 5) {
            $code = $BASE32_CODES[$hashValue];
            array_push($chars, $code);
            $bits = 0;
            $hashValue = 0;
          }
        }

        return implode($chars,'');
    }
    /**
     * Función para cargar el excel 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function importExcel()
    {
        Excel::import(new VaritiesImport, request()->file('file'));
    }

    public function import_bets(){
      $archivo = 'temporal.txt';
      $texto = file_get_contents($archivo); 
      $lines = preg_split('/\r\n|\r|\n/', trim($texto));

      $records = [];
      for ($i = 0; $i < count($lines); $i += 2) {
          // Primera línea: se separa por tabulador
          $line1 = trim($lines[$i]);
          $parts = explode("\t", $line1);
          // Eliminamos elementos vacíos (pueden existir varios tabuladores seguidos)
          $parts = array_values(array_filter(array_map('trim', $parts), function($v) { return $v !== ''; }));

          // Se espera que la primera línea tenga 4 elementos:
          // [0]: "2/19/2025, 11:24:00 PM"
          // [1]: player1 ("Kray")
          // [2]: marcador ("4-2 (3-1)" con posibles espacios especiales)
          // [3]: player2 ("Boulevard")
          if (count($parts) < 4) {
              continue; // o manejar el error según tu lógica
          }

          // Extraer fecha y hora del primer campo (separados por coma)
          $fhParts = explode(',', $parts[0]);
          $fecha = trim($fhParts[0]);
          $hora  = isset($fhParts[1]) ? trim($fhParts[1]) : '';

          $player1 = $parts[1];
          $scoreStr = trim($parts[2]);
          $player2 = $parts[3];

          // Utilizamos una expresión regular con el flag 'u' para soportar UTF-8
          // Se asume el formato "X-Y (A-B)" donde X-Y es full time y A-B es half time

          if (preg_match('/^(\d+)-(\d+)\s*\((\d+)-(\d+)\)$/u', $scoreStr, $matches)) {
              $ft1 = $matches[1];
              $ft2 = $matches[2];
              $ht1 = $matches[3];
              $ht2 = $matches[4];
          } else {
              $ft1 = $ft2 = $ht1 = $ht2 = null;
          }

          // Segunda línea: se separa por tabulador para obtener los equipos
          $line2 = trim($lines[$i + 1]);
          $teamParts = explode("\t", $line2);
          $teamParts = array_values(array_filter(array_map('trim', $teamParts), function($v) { return $v !== ''; }));
          // Se espera que existan dos elementos: team1 y team2
          $team1 = isset($teamParts[0]) ? $teamParts[0] : null;
          $team2 = isset($teamParts[1]) ? $teamParts[1] : null;

          $f = explode("/", $fecha);
          $fecha = $f[2]."-".$f[0]."-".$f[1];
          $hora = date("H:i:s", strtotime($hora));
          $player1 = strtoupper($player1);
          $player2 = strtoupper($player2);
          $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;
          $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;
          if(empty($p1)){
            $p1_data = DB::table('players')->insert(['name' => $player1]);
            $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;
          }

          if(empty($p2)){
            $p2_data = DB::table('players')->insert(['name' => $player2]);
            $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;
          }
          // Armar el arreglo final con el orden solicitado:
          // fecha, hora, player1, ht1, ft1, player2, ht2, ft2, team1, team2
          $records = [
              'fecha'   => $fecha,
              'hora'    => $hora,
              'player1' => $player1,
              'p1' => $p1,
              'ht1'     => $ht1,
              'ft1'     => $ft1,
              'player2' => $player2,
              'p2' => $p2,
              'ht2'     => $ht2,
              'ft2'     => $ft2,
              'team1'   => $team1,
              'team2'   => $team2,
              'spread'  => ($ft1+$ft2) - ($ht1+$ht2)
          ];

          $existe = Ht_result::where('fecha', $fecha)
                                ->where('hora',$hora)
                                ->where('player1',$player1)
                                ->where('player2',$player2)
                                ->first();

          empty($existe) ? Ht_result::create($records): "";

        }

        return json_encode("ok");
    }

    public function info_bets(){
      $from = "2024-09-01";
      /*
      $resultados_global = DB::table('bets')
        ->join('players as p1', 'bets.player1', '=', 'p1.id') // Join para el jugador
        ->join('players as p2', 'bets.player2', '=', 'p2.id') // Join para el enemigo
        ->selectRaw('
            CASE 
                WHEN bets.player1 < bets.player2 THEN p1.name 
                ELSE p2.name 
            END as jugador_principal,
            CASE 
                WHEN bets.player1 < bets.player2 THEN p2.name 
                ELSE p1.name 
            END as enemigo_principal,
            COUNT(*) as partidos_jugados,
            SUM(CASE WHEN (finals1 + finals2 < 3) THEN 1 ELSE 0 END) as minor,
            SUM(CASE WHEN (finals1 + finals2 = 3) THEN 1 ELSE 0 END) as three,
            SUM(CASE WHEN (finals1 + finals2 = 4) THEN 1 ELSE 0 END) as four,
            SUM(CASE WHEN (finals1 + finals2 = 5) THEN 1 ELSE 0 END) as five,
            SUM(CASE WHEN (finals1 + finals2 = 6) THEN 1 ELSE 0 END) as six
        ')
        ->whereBetween('bets.fecha', [$from, now()])
        ->groupBy('jugador_principal', 'enemigo_principal');
        */

        $resultados_global = DB::table('bets')
          ->join('players as p1', 'bets.player1', '=', 'p1.id')
          ->join('players as p2', 'bets.player2', '=', 'p2.id')
          ->selectRaw("
              LEAST(p1.name, p2.name) as jugador_principal,
              GREATEST(p1.name, p2.name) as enemigo_principal,
              COUNT(*) as partidos_jugados,
              SUM(CASE WHEN (finals1 + finals2 < 3) THEN 1 ELSE 0 END) as minor,
              SUM(CASE WHEN (finals1 + finals2 = 3) THEN 1 ELSE 0 END) as three,
              SUM(CASE WHEN (finals1 + finals2 = 4) THEN 1 ELSE 0 END) as four,
              SUM(CASE WHEN (finals1 + finals2 = 5) THEN 1 ELSE 0 END) as five,
              SUM(CASE WHEN (finals1 + finals2 = 6) THEN 1 ELSE 0 END) as six,
              bets.avg
          ")
          ->whereBetween('bets.fecha', [$from, now()])
          ->groupBy(DB::raw('LEAST(p1.id, p2.id), GREATEST(p1.id, p2.id)'));


      if(isset($_GET['player'])){

        $player = $_GET['player'];
        $resultados_global = $resultados_global->where(function ($query) use ($player) {
                  $query->whereIn('player1', $player)
                        ->orWhereIn('player2', $player);
                  });
      }

      $resultados_global = $resultados_global->get();

      $players = DB::table('players')->selectRaw('id,name')->pluck('name','id');

      return view('bets/info_bets', compact('resultados_global','players'));
    }

    public function betshtft(){
      $from = "2025-01-01";
      
      $resultados = DB::table('ht_results')
        ->selectRaw("
            player1 as jugador_principal,
            player2 as enemigo_principal,
            COUNT(*) as partidos_jugados,
            AVG(ht1) as ht_1,
            AVG(ht2) as ht_2,
            AVG(SUM(ht2+ht1)) as ht_final,
            AVG(ft1) as ft_1,
            AVG(ft2) as ft_2,
            AVG(SUM(ft2+ft1)) as ft_final,
            SUM(CASE WHEN (ft1 + ft2 < 3) THEN 1 ELSE 0 END) as minor,
            SUM(CASE WHEN (ft1 + ft2 = 3) THEN 1 ELSE 0 END) as three,
            SUM(CASE WHEN (ft1 + ft2 = 4) THEN 1 ELSE 0x END) as four,
            SUM(CASE WHEN (ft1 + ft2 = 5) THEN 1 ELSE 0 END) as five,
            SUM(CASE WHEN (ft1 + ft2 = 6) THEN 1 ELSE 0 END) as six,
        ")
        #->whereBetween('bets.fecha', [$from, now()])
        ->groupBy('jugador_principal','enemigo_principal');


      if(isset($_GET['player'])){

        $player = $_GET['player'];
        $resultados_global = $resultados_global->where(function ($query) use ($player) {
                  $query->whereIn('player1', $player)
                        ->orWhereIn('player2', $player);
                  });
      }

      foreach ($resultados as $ke => $ve) {
        $jugador = $ve->jugador_principal;

        if(isset($players) && count($players) > 1){
            foreach ($players as $xx) {
              if ($xx == strtoupper($jugador) || $xx == strtoupper($ve->enemigo_principal)) {
                // Inicializa el array asociativo si aún no existe
                if (!isset($resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]) && !isset($resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)])) {
                    $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)] = [
                        'jugador' => strtoupper($jugador),
                        'enemigo' => strtoupper($ve->enemigo_principal),
                        'partidos_jugados' => 0,
                        'ht_1' => 0,
                        'ht_2' => 0,
                        'ht_final' => 0,
                        'ft_1' => 0,
                        'ft_2' => 0,
                        'ft_final' => 0,
                        '5_5' => 0,
                        '6_5' => 0,
                    ];
                }

                if(isset($resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)])){
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['partidos_jugados'] += $ve->partidos_jugados;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['aciertos'] += $ve->aciertos;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['fallos'] += $ve->fallos;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['0_5'] += $ve->zero;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['1_5'] += $ve->one;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['3_5'] += $ve->tree;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['4_5'] += $ve->four;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['5_5'] += $ve->five;
                  $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['6_5'] += $ve->six;
                }else{
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['partidos_jugados'] += $ve->partidos_jugados;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['aciertos'] += $ve->aciertos;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['fallos'] += $ve->fallos;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['0_5'] += $ve->zero;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['1_5'] += $ve->one;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['3_5'] += $ve->tree;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['4_5'] += $ve->four;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['5_5'] += $ve->five;
                  $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['6_5'] += $ve->six;

                }
              }
            }
          }else{
            // Inicializa el array asociativo si aún no existe
            if (!isset($resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]) && !isset($resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)])) {
                $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)] = [
                    'jugador' => strtoupper($jugador),
                    'enemigo' => strtoupper($ve->enemigo_principal),
                    'partidos_jugados' => 0,
                    'aciertos' => 0,
                    'fallos' => 0,
                    '0_5' => 0,
                    '1_5' => 0,
                    '3_5' => 0,
                    '4_5' => 0,
                    '5_5' => 0,
                    '6_5' => 0,
                ];
            }

            if(isset($resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)])){
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['partidos_jugados'] += $ve->partidos_jugados;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['aciertos'] += $ve->aciertos;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['fallos'] += $ve->fallos;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['0_5'] += $ve->zero;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['1_5'] += $ve->one;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['3_5'] += $ve->tree;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['4_5'] += $ve->four;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['5_5'] += $ve->five;
              $resultadosTotalesEne[strtoupper($ve->enemigo_principal)][strtoupper($jugador)]['6_5'] += $ve->six;
            }else{
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['partidos_jugados'] += $ve->partidos_jugados;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['aciertos'] += $ve->aciertos;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['fallos'] += $ve->fallos;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['0_5'] += $ve->zero;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['1_5'] += $ve->one;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['3_5'] += $ve->tree;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['4_5'] += $ve->four;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['5_5'] += $ve->five;
              $resultadosTotalesEne[strtoupper($jugador)][strtoupper($ve->enemigo_principal)]['6_5'] += $ve->six;
            }
          }
      }

      $resultados_global = $resultados_global->get();

      $players = DB::table('players')->selectRaw('id,name')->pluck('name','id');

      return view('bets/bets_ht_ft', compact('resultados_global','players'));
    }

    public function filterEnemies(Request $request)
{
    // Procesar el filtro de fecha, si se envía
    if ($request->has('daterange') && $request->input('daterange') != "") {
        $daterange = $request->input('daterange');
        $from = explode(" - ", $daterange)[0];
        $to   = explode(" - ", $daterange)[1];
        $from = date("Y-m-d", strtotime($from));
        $to = date("Y-m-d", strtotime($to));
    } else {
        // Valores por defecto o manejar la ausencia del filtro de fecha
        $from = '2025-01-01';
        $to = '2025-12-31';
    }

    // Consulta similar a la original para la tabla de enemigos
    $resultadosEne = DB::table('ht_results')
        ->selectRaw('
            player1 as jugador,
            player2 as enemigo,
            fecha, team1,
            COUNT(*) as partidos_jugados,
            SUM(case when (ft1+ft2 > 0) then 1 else 0 end) as zero,
            SUM(case when (ft1+ft2 > 1) then 1 else 0 end) as one,
            SUM(case when (ft1+ft2 > 2) then 1 else 0 end) as two,
            SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as tree,
            SUM(case when (ft1+ft2 > 4) then 1 else 0 end) as four,
            SUM(case when (ft1+ft2 > 5) then 1 else 0 end) as five,
            SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as six,
            SUM(case when (spread > 0) then 1 else 0 end) as spzero,
            SUM(case when (spread > 1) then 1 else 0 end) as spone,
            SUM(case when (spread > 2) then 1 else 0 end) as sptwo,
            SUM(case when (spread > 3) then 1 else 0 end) as sptree,
            SUM(case when (spread > 4) then 1 else 0 end) as spfour,
            SUM(case when (spread > 5) then 1 else 0 end) as spfive,
            SUM(case when (spread > 6) then 1 else 0 end) as spsix
        ')
        ->where('player1', '!=', "")
        ->whereBetween('fecha', [$from, $to]);

    // Aplicar filtro por FT1 y FT2 si se envían
    if ($request->filled('ft1_filter') && $request->filled('ft2_filter')) {
        $ft1 = $request->input('ft1_filter');
        $ft2 = $request->input('ft2_filter');
        $resultadosEne = $resultadosEne->where('ft1', $ft1)
                                       ->where('ft2', $ft2);
    }

    $resultadosEne = $resultadosEne->groupBy('jugador','enemigo')->get();

    // Generar el HTML para las filas de la tabla
    $html = '';
    if(!empty($resultadosEne)) {
        foreach ($resultadosEne as $ene) {
            // Cálculo de porcentajes (adaptado de tu código original)
            $por_05 = $por_15 = $por_25 = $por_35 = $por_45 = $por_55 = 0;
            $por_05sp = $por_15sp = $por_25sp = $por_35sp = $por_45sp = $por_55sp = 0;
            if($ene->partidos_jugados > 0){
                $por_05 = round($ene->zero * 100 / $ene->partidos_jugados);
                $por_15 = round($ene->one * 100 / $ene->partidos_jugados);
                $por_25 = round($ene->two * 100 / $ene->partidos_jugados);
                $por_35 = round($ene->tree * 100 / $ene->partidos_jugados);
                $por_45 = round($ene->four * 100 / $ene->partidos_jugados);
                $por_55 = round($ene->five * 100 / $ene->partidos_jugados);
                
                $por_05sp = round($ene->spzero * 100 / $ene->partidos_jugados);
                $por_15sp = round($ene->spone * 100 / $ene->partidos_jugados);
                $por_25sp = round($ene->sptwo * 100 / $ene->partidos_jugados);
                $por_35sp = round($ene->sptree * 100 / $ene->partidos_jugados);
                $por_45sp = round($ene->spfour * 100 / $ene->partidos_jugados);
                $por_55sp = round($ene->spfive * 100 / $ene->partidos_jugados);
            }
            
            $html .= '<tr>';
            $html .= '<td>' . $ene->jugador . '</td>';
            $html .= '<td>' . $ene->enemigo . '</td>';
            $html .= '<td>' . $ene->partidos_jugados . '</td>';
            $html .= '<td>' . $por_05 . '</td>';
            $html .= '<td>' . $por_15 . '</td>';
            $html .= '<td>' . $por_25 . '</td>';
            $html .= '<td>' . $por_35 . '</td>';
            $html .= '<td>' . $por_45 . '</td>';
            $html .= '<td>' . $por_55 . '</td>';
            $html .= '<td>' . $por_05sp . '</td>';
            $html .= '<td>' . $por_15sp . '</td>';
            $html .= '<td>' . $por_25sp . '</td>';
            $html .= '<td>' . $por_35sp . '</td>';
            $html .= '<td>' . $por_45sp . '</td>';
            $html .= '<td>' . $por_55sp . '</td>';
            $html .= '<td><button type="button" class="btn btn-outline-warning compare_players" attr-player1="' . $ene->jugador . '" attr-player2="' . $ene->enemigo . '">Comparar</button></td>';
            $html .= '</tr>';
        }
    }
    return response()->json(['html' => $html]);
}


    public function process_spreads(){
      $data = Ht_result::all();

      foreach ($data as $key => $value) {
        $spread = ($value->ft1 + $value->ft2) - ($value->ht1 + $value->ht2);
        $d = Ht_result::find($value->id);
        $d->spread = $spread;
        $d->save();
      }

    }


    public function playersByGroups(Request $request)
    {
        $groups = $request->get('groups', []);  // array de grupos seleccionados
        if (empty($groups)) {
            return response()->json(['results' => []]);
        }

        $players = DB::table('players')->whereIn('group_p', $groups)
                         ->select('id', 'name')
                         ->orderBy('name')
                         ->get()
                         ->map(fn($p) => ['id' => $p->id, 'text' => $p->name]);

        return response()->json(['results' => $players]);
    }

    // ----------------------------------------------
// Ejemplo de grid search para optimizar pesos
function optimizeWeights(array $historical): array
{
    $best = ['accuracy' => 0, 'weights' => []];
    // Rango de pesos para Under: u5 (5.5), u3 (3.5), usp (spread)
    for ($u5 = 0.4; $u5 <= 0.8; $u5 += 0.1) {
        for ($u3 = 0.1; $u3 <= 0.4; $u3 += 0.1) {
            $usp = 1 - ($u5 + $u3);
            if ($usp < 0.1 || $usp > 0.4) continue;
            // Rango de pesos para Over: o45, o55, o65, osp
            for ($o45 = 0.3; $o45 <= 0.6; $o45 += 0.1) {
                for ($o55 = 0.1; $o55 <= 0.3; $o55 += 0.1) {
                    for ($o65 = 0.05; $o65 <= 0.2; $o65 += 0.05) {
                        $osp = 1 - ($o45 + $o55 + $o65);
                        if ($osp < 0.1 || $osp > 0.4) continue;
                        $correct = 0;
                        $total   = count($historical);
                        // Backtest: recorre histórico y calcula etiquetas
                        foreach ($historical as $row) {
                            $uScore = $u5 * ($row['zero']/$row['partidos_jugados'])
                                    + $u3 * ($row['two']/$row['partidos_jugados'])
                                    + $usp * (($row['spzero']+$row['spone']+$row['sptwo'])/$row['partidos_jugados']);
                            $oScore = $o45 * ($row['four']/$row['partidos_jugados'])
                                    + $o55 * ($row['five']/$row['partidos_jugados'])
                                    + $o65 * ($row['six']/$row['partidos_jugados'])
                                    + $osp * (($row['sptwo']+$row['sptree']+$row['spsix'])/$row['partidos_jugados']);
                            // Etiqueta predicha
                            $pred = $uScore >= 0.75 && $uScore > $oScore ? 'Under-Fav'
                                  : ($oScore >= 0.80 && $oScore > $uScore ? 'Over-Fav' : 'Neutral');
                            // Compara con histórico real (ej: $row['real_tag'])
                            if (isset($row['real_tag']) && $pred === $row['real_tag']) {
                                $correct++;
                            }
                        }
                        $acc = $correct/$total;
                        if ($acc > $best['accuracy']) {
                            $best = ['accuracy' => $acc, 'weights' => compact('u5','u3','usp','o45','o55','o65','osp')];
                        }
                    }
                }
            }
        }
    }
    return $best;
}


  public function bets(Request $request)
    {
        $query = Ht_result::query();

        // 1. Filtro de Fechas
        if ($request->filled('daterange')) {
            [$start, $end] = explode(' - ', $request->daterange);
            $query->whereBetween('fecha', [Carbon::parse($start), Carbon::parse($end)]);
        } else {
            // Default actual year
            $query->whereYear('fecha', Carbon::now()->year);
        }

        // 2. Filtro de Jugadores
        if ($request->filled('player')) {
            $players = $request->player;
            $query->where(function($q) use ($players) {
                $q->whereIn('player1', $players)->orWhereIn('player2', $players);
            });
        }

        // --- OPTIMIZACIÓN CLAVE ---
        
        // A. Agrupación Principal (Estadísticas por Jugador)
        // Clonamos query para no perder filtros
        $agrup = (clone $query)
            ->whereNotNull('player1')
            ->groupBy('player1')
            ->addSelect('player1 as jugador') // Necesario para el group by
            ->selectStats() // <--- LLAMADA AL SCOPE MÁGICO
            ->get();

        // B. Agrupación Enemigos (H2H)
        $eneQuery = (clone $query)->whereNotNull('player1')->whereNotNull('player2');
        
        // Filtros extra específicos de la vista enemigos
        if ($request->filled(['ft1_filter', 'ft2_filter'])) {
            $eneQuery->where('ht1', $request->ft1_filter) // Ojo: en tu código original filtrabas HT usando inputs llamados FT? Ajusta si es necesario
                     ->where('ht2', $request->ft2_filter);
        }

        $agrupEne = $eneQuery
            ->groupBy('player1', 'player2')
            ->addSelect('player1 as jugador', 'player2 as enemigo', 'p1', 'p2')
            ->selectStats()
            ->get();

        // C. Lista de apuestas (Tabla inferior)
        $bets = (clone $query)->latest('fecha')->limit(200)->get();

        // D. Datos auxiliares (Optimizados con pluck directo)
        $players = Player::pluck('name', 'id');
        $groups = Player::whereNotNull('group_p')->distinct()->pluck('name', 'group_p');
        $players_array = $players->toArray();
        $public_ip = $request->ip();

        return view('bets.index', compact('bets', 'players', 'agrup', 'agrupEne', 'players_array', 'groups', 'public_ip'));
    }


    public function bets_compare(Request $request)
    {
        // Se obtienen los parámetros enviados
        $player1 = $request->input('player1');
        $player2 = $request->input('player2');
        $ht1 = $request->input('ht1'); // goles HT para el player1
        $ht2 = $request->input('ht2'); // goles HT para el player2

        $teams = Ht_result::getTeamOptions();

        $actualDate = Carbon::now();
        $d_30 = $actualDate->copy()->subDays(30)->format('Y-m-d');
        $d_10 = $actualDate->copy()->subDays(10)->format('Y-m-d');
        $d_3  = $actualDate->copy()->subDays(3)->format('Y-m-d');
        $d_7  = $actualDate->copy()->subDays(7)->format('Y-m-d');
        $actual = $actualDate->format('Y-m-d'); 

        // Consulta global para attr-player1
        $global_p1 = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, team1,
                COUNT(*) as partidos_jugados, ft1, ft2, ht1, ht2,
                SUM(case when (ft1+ft2 > 0) then 1 else 0 end) as zero,
                SUM(case when (ft1+ft2 > 1) then 1 else 0 end) as one,
                SUM(case when (ft1+ft2 > 2) then 1 else 0 end) as two,
                SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as tree,
                SUM(case when (ft1+ft2 > 4) then 1 else 0 end) as four,
                SUM(case when (ft1+ft2 > 5) then 1 else 0 end) as five,
                SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as six,
                SUM(case when (spread > 0) then 1 else 0 end) as spzero,
                SUM(case when (spread > 1) then 1 else 0 end) as spone,
                SUM(case when (spread > 2) then 1 else 0 end) as sptwo,
                SUM(case when (spread > 3) then 1 else 0 end) as sptree,
                SUM(case when (spread > 4) then 1 else 0 end) as spfour,
                SUM(case when (spread > 5) then 1 else 0 end) as spfive,
                SUM(case when (spread > 6) then 1 else 0 end) as spsix
            ')
            ->where(function($query) use ($player1) {
                $query->where('player1', $player1)
                      ->orWhere('player2', $player1);
            });
        // Filtrar por goles HT si se enviaron
        if ($ht1 !== null && $ht1 !== '') {
            $global_p1->where('ht1', $ht1);
        }
        if ($ht2 !== null && $ht2 !== '') {
            $global_p1->where('ht2', $ht2);
        }

        // Consulta global para player2
        $global_p2 = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, team1,
                COUNT(*) as partidos_jugados, ft1, ft2, ht1, ht2,
                SUM(case when (ft1+ft2 > 0) then 1 else 0 end) as zero,
                SUM(case when (ft1+ft2 > 1) then 1 else 0 end) as one,
                SUM(case when (ft1+ft2 > 2) then 1 else 0 end) as two,
                SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as tree,
                SUM(case when (ft1+ft2 > 4) then 1 else 0 end) as four,
                SUM(case when (ft1+ft2 > 5) then 1 else 0 end) as five,
                SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as six,
                SUM(case when (spread > 0) then 1 else 0 end) as spzero,
                SUM(case when (spread > 1) then 1 else 0 end) as spone,
                SUM(case when (spread > 2) then 1 else 0 end) as sptwo,
                SUM(case when (spread > 3) then 1 else 0 end) as sptree,
                SUM(case when (spread > 4) then 1 else 0 end) as spfour,
                SUM(case when (spread > 5) then 1 else 0 end) as spfive,
                SUM(case when (spread > 6) then 1 else 0 end) as spsix
            ')
            ->where(function($query) use ($player2) {
                $query->where('player1', $player2)
                      ->orWhere('player2', $player2);
            });
        if ($ht1 !== null && $ht1 !== '') {
            $global_p2->where('ht1', $ht1);
        }
        if ($ht2 !== null && $ht2 !== '') {
            $global_p2->where('ht2', $ht2);
        }

        // Obtención de datos para distintos períodos
        $general_mes_p1 = $global_p1->whereDate('fecha', '>=', $d_30)->get();
        $general_mes_p2 = $global_p2->whereDate('fecha', '>=', $d_30)->get();
        $general_sem_p1 = $global_p1->whereDate('fecha', '>=', $d_10)->get();
        $general_sem_p2 = $global_p2->whereDate('fecha', '>=', $d_10)->get();
        $general_dia_p1 = $global_p1->whereDate('fecha', '>=', $actual)->get();
        $general_dia_p2 = $global_p2->whereDate('fecha', '>=', $actual)->get();

        // Comparación entre ambos jugadores
        $global_partidos = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, team1,
                COUNT(*) as partidos_jugados, ft1, ft2, ht1, ht2,
                SUM(case when (ft1+ft2 > 0) then 1 else 0 end) as zero,
                SUM(case when (ft1+ft2 > 1) then 1 else 0 end) as one,
                SUM(case when (ft1+ft2 > 2) then 1 else 0 end) as two,
                SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as tree,
                SUM(case when (ft1+ft2 > 4) then 1 else 0 end) as four,
                SUM(case when (ft1+ft2 > 5) then 1 else 0 end) as five,
                SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as six,
                SUM(case when (spread > 0) then 1 else 0 end) as spzero,
                SUM(case when (spread > 1) then 1 else 0 end) as spone,
                SUM(case when (spread > 2) then 1 else 0 end) as sptwo,
                SUM(case when (spread > 3) then 1 else 0 end) as sptree,
                SUM(case when (spread > 4) then 1 else 0 end) as spfour,
                SUM(case when (spread > 5) then 1 else 0 end) as spfive,
                SUM(case when (spread > 6) then 1 else 0 end) as spsix
            ');

        $global_partidos_sin_ht = clone $global_partidos;

        $global_partidos = $global_partidos->where(function($query) use ($player1, $player2,$ht1,$ht2) {
                $query->where(function($q) use ($player1, $player2,$ht1,$ht2) {
                    $q->where('player1', $player1)
                      ->where('player2', $player2);
                  if ($ht1 != '') {
                    $q->where('ht1',$ht1);
                    $q->where('ht2',$ht2);
                  }

                })->orWhere(function($q) use ($player1, $player2,$ht1,$ht2) {
                    $q->where('player1', $player2)
                      ->where('player2', $player1);

                      if ($ht1 != '') {
                        $q->where('ht1',$ht2);
                        $q->where('ht2',$ht1);
                      }
                });
            });

        $global_partidos_sin_ht = $global_partidos_sin_ht->where(function($query2) use ($player1, $player2,$ht1,$ht2) {
                $query2->where(function($q2) use ($player1, $player2,$ht1,$ht2) {
                    $q2->where('player1', $player1)
                      ->where('player2', $player2);
                })->orWhere(function($q2) use ($player1, $player2,$ht1,$ht2) {
                    $q2->where('player1', $player2)
                      ->where('player2', $player1);
                });
            });
        

        // Datos de enfrentamientos en distintos periodos
        $global_vs = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, team1,
                COUNT(*) as partidos_jugados, ft1, ft2, ht1, ht2,
                SUM(case when (ft1+ft2 > 0) then 1 else 0 end) as zero,
                SUM(case when (ft1+ft2 > 1) then 1 else 0 end) as one,
                SUM(case when (ft1+ft2 > 2) then 1 else 0 end) as two,
                SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as tree,
                SUM(case when (ft1+ft2 > 4) then 1 else 0 end) as four,
                SUM(case when (ft1+ft2 > 5) then 1 else 0 end) as five,
                SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as six,
                SUM(case when (spread > 0) then 1 else 0 end) as spzero,
                SUM(case when (spread > 1) then 1 else 0 end) as spone,
                SUM(case when (spread > 2) then 1 else 0 end) as sptwo,
                SUM(case when (spread > 3) then 1 else 0 end) as sptree,
                SUM(case when (spread > 4) then 1 else 0 end) as spfour,
                SUM(case when (spread > 5) then 1 else 0 end) as spfive,
                SUM(case when (spread > 6) then 1 else 0 end) as spsix
            ')
             ->where(function($q) use ($player1, $player2) {
                $q->where(function($sub) use ($player1, $player2) {
                    $sub->where('player1', $player1)
                        ->where('player2', $player2);
                })->orWhere(function($sub) use ($player1, $player2) {
                    $sub->where('player1', $player2)
                        ->where('player2', $player1);
                });
            });
        if ($ht1 !== null && $ht1 !== '') {
            $global_vs->where('ht1', $ht1);
        }
        if ($ht2 !== null && $ht2 !== '') {
            $global_vs->where('ht2', $ht2);
        }
        $global_vs_week   = $global_vs->whereDate('fecha', '>=', $d_7)->get();
        $global_vs_tree   = $global_vs->whereDate('fecha', '>=', $d_3)->get();
        $global_vs_actual = $global_vs->whereDate('fecha', '>=', $actual)->get();

        // Detalle de enfrentamientos
        $global_vs_det = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, hora, team1, team2, ft1, ft2, ht1, ht2')
            ->where(function($query) use ($player1, $player2,$ht1,$ht2) {
                $query->where(function($q) use ($player1, $player2,$ht1,$ht2) {
                    $q->where('player1', $player1)
                      ->where('player2', $player2);
                  if ($ht1 !== '') {
                    $q->where('ht1',$ht1);
                    $q->where('ht2',$ht2);
                  }

                })->orWhere(function($q) use ($player1, $player2,$ht1,$ht2) {
                    $q->where('player1', $player2)
                      ->where('player2', $player1);

                      if ($ht1 !== '') {
                        $q->where('ht1',$ht2);
                        $q->where('ht2',$ht1);
                      }
                });
            })
            ->whereDate('fecha', '>=', $d_7)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();



        // Consulta para vs_p1
        $vs_p1 = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, hora, team1, team2, 
                 ft1, ft2, ht1, ht2')
            ->where(function($query) use ($player1) {
                $query->where('player1', $player1)
                      ->orWhere('player2', $player1);
            })
            ->whereDate('fecha', '>=', $d_3)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')->get();

        // Consulta para vs_p2
        $vs_p2 = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, hora, team1, team2, 
               ft1, ft2, ht1, ht2')
            ->where(function($query) use ($player2) {
                $query->where('player1', $player2)
                      ->orWhere('player2', $player2);
            })
            ->whereDate('fecha', '>=', $d_3)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')->get();

        // Consulta para global_partidos_team
        $global_partidos_team = DB::table('ht_results')
            ->selectRaw('player1, player2, fecha, hora, team1, team2,
                COUNT(*) as partidos_jugados, ft1, ft2, ht1, ht2,
                SUM(case when (ft1+ft2 > 0) then 1 else 0 end) as zero,
                SUM(case when (ft1+ft2 > 1) then 1 else 0 end) as one,
                SUM(case when (ft1+ft2 > 2) then 1 else 0 end) as two,
                SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as tree,
                SUM(case when (ft1+ft2 > 4) then 1 else 0 end) as four,
                SUM(case when (ft1+ft2 > 5) then 1 else 0 end) as five,
                SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as six,
                SUM(case when (spread > 0) then 1 else 0 end) as spzero,
                SUM(case when (spread > 1) then 1 else 0 end) as spone,
                SUM(case when (spread > 2) then 1 else 0 end) as sptwo,
                SUM(case when (spread > 3) then 1 else 0 end) as sptree,
                SUM(case when (spread > 4) then 1 else 0 end) as spfour,
                SUM(case when (spread > 5) then 1 else 0 end) as spfive,
                SUM(case when (spread > 6) then 1 else 0 end) as spsix
            ');
        if ($ht1 !== null && $ht1 !== '') {
            $global_partidos_team->where('ht1', $ht1);
        }
        if ($ht2 !== null && $ht2 !== '') {
            $global_partidos_team->where('ht2', $ht2);
        }

        // Si se filtran además por equipos (opcional)
        $team1 = $request->input('team1');
        $team2 = $request->input('team2');
        if (!empty($team1) && !empty($team2)) {
            $global_partidos_team->where(function($query) use ($player1, $player2, $team1, $team2) {
                $query->where('player1', $player1)
                      ->where('player2', $player2)
                      ->where('team1', $team1)
                      ->where('team2', $team2);
            })->orWhere(function($query) use ($player1, $player2, $team1, $team2) {
                $query->where('player1', $player2)
                      ->where('player2', $player1)
                      ->where('team1', $team2)
                      ->where('team2', $team1);
            });
        } else {
            $global_partidos_team->where(function($query) use ($player1, $player2) {
                $query->where('player1', $player1)
                      ->where('player2', $player2);
            })->orWhere(function($query) use ($player1, $player2) {
                $query->where('player1', $player2)
                      ->where('player2', $player1);
            })->groupBy('team1', 'team2');
        }

        // Se obtiene la lista de players
        $players = DB::table('players')->selectRaw('id, name')->pluck('name', 'id')->toArray();

        // Se preparan los arreglos para la vista
        $data_p1 = [
            'mensual' => $general_mes_p1->toArray(),
            'semanal' => $general_sem_p1->toArray(),
            'actual'  => $general_dia_p1->toArray(),
        ];
        $data_p2 = [
            'mensual' => $general_mes_p2->toArray(),
            'semanal' => $general_sem_p2->toArray(),
            'actual'  => $general_dia_p2->toArray(),
        ];

        $actual = Carbon::now();
        $d_30 = $actual->copy()->subDays(30)->format('Y-m-d');
        $d_10 = $actual->copy()->subDays(10)->format('Y-m-d');
        $d_3 = $actual->copy()->subDays(3)->format('Y-m-d');
        $d_7 = $actual->copy()->subDays(7)->format('Y-m-d');
        $actual = $actual->format('Y-m-d');

        // 1. Define tu consulta base (tu modelo o lo que sea que devuelva $global_partidos)
        $baseQuery = $global_partidos;
        $baseQuery_sin_ht = $global_partidos_sin_ht;

        // 2. Lista de umbrales de fechas
        $thresholds = [
          'global'   => "",
          'actual'   => $actual,
          '3_dias'   => $d_3,
          '7_dias'   => $d_7,
          '10_dias'  => $d_10,
          '30_dias'  => $d_30,
        ];

      // 3. Variable donde iremos acumulando y mergeando los resultados
      $global_partidos = collect();
      $global_partidos_sin_ht = collect();

      // 4. Iteramos, clonamos la consulta base, filtramos y hacemos merge
      foreach ($thresholds as $label => $date) {
          // clonamos la query, filtramos y obtenemos resultados
          $subset = (clone $baseQuery)
              ->whereDate('fecha', '>=', $date)
              ->get()
              // aquí le añadimos un atributo "umbral" a cada modelo
              ->map(function($partido) use ($label) {
                  // Laravel permite añadir atributos dinámicos
                  $label == "global" ? $label_txt = 1000 : "";
                  $label == "actual" ? $label_txt = 0 : "";
                  $label == "3_dias" ? $label_txt = 3 : "";
                  $label == "7_dias" ? $label_txt = 7 : "";
                  $label == "10_dias" ? $label_txt = 10 : "";
                  $label == "30_dias" ? $label_txt = 30 : "";
                  $partido->umbral = $label_txt;
                  return $partido;
              });
          $subset_sin_ht = (clone $baseQuery_sin_ht)
              ->whereDate('fecha', '>=', $date)
              ->get()
              // aquí le añadimos un atributo "umbral" a cada modelo
              ->map(function($partido_sin_ht) use ($label) {
                  // Laravel permite añadir atributos dinámicos
                  $label == "global" ? $label_txt = 1000 : "";
                  $label == "actual" ? $label_txt = 0 : "";
                  $label == "3_dias" ? $label_txt = 3 : "";
                  $label == "7_dias" ? $label_txt = 7 : "";
                  $label == "10_dias" ? $label_txt = 10 : "";
                  $label == "30_dias" ? $label_txt = 30 : "";
                  $partido_sin_ht->umbral = $label_txt;
                  return $partido_sin_ht;
              });

          // lo acumulamos
          $global_partidos = $global_partidos->merge($subset);
          $global_partidos_sin_ht = $global_partidos_sin_ht->merge($subset_sin_ht);
        }


        /*
        $global_partidos = $global_partidos->get();
        $global_partidos_3 = $global_partidos_3->get();
        */
        $bet2eneteam = array();

        return view('bets.compare', compact(
            'data_p1',
            'data_p2',
            'global_partidos',
            'global_partidos_sin_ht',
            'global_vs_actual',
            'global_vs_tree',
            'global_vs_week',
            'global_partidos_team',
            'players',
            'player1',
            'player2',
            'global_vs_det',
            'vs_p1',
            'vs_p2',
            'teams'
        ));
    }

public function bets_summary(Request $request)
{
    $player1 = $request->input('player1');
    $player2 = $request->input('player2');
    $ht1     = $request->input('ht1');
    $ht2     = $request->input('ht2');

    // Ventanas (TZ Chile)
    $today = \Carbon\Carbon::now('America/Santiago')->startOfDay();
    $windows = [
        'today' => $today->copy()->toDateString(),
        '3d'    => $today->copy()->subDays(3)->toDateString(),
        '7d'    => $today->copy()->subDays(7)->toDateString(),
        '14d'   => $today->copy()->subDays(14)->toDateString(),
        '30d'   => $today->copy()->subDays(30)->toDateString(),
        'all'   => null,
    ];
    $windowOrder = array_keys($windows);
    $windowsForCoherence = ['3d','7d','14d','30d','all']; // omitimos "today" para coherencia
    $trustedWindows      = ['30d','all'];

    // Markets a evaluar
    $markets = $request->input('markets', ['1.5','2.5','3.5','4.5','5.5','6.5']);

    // Columnas que devuelve tu get_data_vs
    $marketMapping = [
        '0.5' => 'porcentaje_zero',
        '1.5' => 'porcentaje_one',
        '2.5' => 'porcentaje_two',
        '3.5' => 'porcentaje_tree',
        '4.5' => 'porcentaje_four',
        '5.5' => 'porcentaje_five',
        '6.5' => 'porcentaje_six',
    ];

    // ===== UMBRALES (ajústalos si quieres) =====
    // Mínimo HT por ventana para considerar la ventana (STRONG)
    $minAbsHTStrong = [
        'today'=>5,  // hoy es muy volátil; si quieres excluirlo siempre pon 999
        '3d'  =>5,
        '7d'  =>6,
        '14d' =>8,
        '30d' =>10,
        'all' =>12,
    ];
    // Mínimo Global por ventana
    $minAbsGlobal = [
        'today'=>5,
        '3d'  =>12,
        '7d'  =>15,
        '14d' =>25,
        '30d' =>40,
        'all' =>60,
    ];

    // Fuerza de porcentaje (HT) para validar dirección de la ventana
    $minPctStrongHT      = 70; // ≥70% en HT (OVER o UNDER)
    $minPctSupportGlobal = 60; // Global debe apoyar en la misma dirección con ≥60%

    // Coherencia por market
    $minValidWindowsSameDir = 3; // nº mínimo de ventanas válidas en misma dirección
    $needTrustedWindow      = true; // exigir 30d o all válidas en esa dirección

    // GAP/Z informativos
    $clarityPP = 10;  // solo para reportar si Global es claro (no bloquea)
    $maxGapPP  = 12;  // gap bajo informativo (no bloquea)
    // (Z y GAP NO bloquean; sólo describen estabilidad/desviación)

    $summary = [
        'player1' => $player1,
        'player2' => $player2,
        'ht1'     => $ht1,
        'ht2'     => $ht2,
        'markets' => $markets,
        'windows' => $windowOrder,
        'results' => [],
        'market_recommendations' => [],
        'recommendation' => null,
    ];

    // ===== PASO 1: métricas por mercado/ventana =====
    foreach ($markets as $mkt) {
        $ftCol = $marketMapping[$mkt] ?? null;
        if (!$ftCol) continue;

        $summary['results'][$mkt] = [];

        foreach ($windows as $wName => $wDate) {
            // Filtrado por HT (con ht1/ht2) y Global (sin ht1/ht2), como ya usabas:
            $fil = $this->get_data_vs($ht1, $ht2, $wDate, $player1, $player2)[0] ?? null;
            $glo = $this->get_data_vs(null,  null,  $wDate, $player1, $player2)[0] ?? null;

            $n_ht = (int)($fil->partidos ?? 0);
            $n_gl = (int)($glo->partidos ?? 0);

            $over_ht = $fil ? (int)round((float)($fil->{$ftCol} ?? 0)) : 0;
            $over_gl = $glo ? (int)round((float)($glo->{$ftCol} ?? 0)) : 0;

            $under_ht = max(0, 100 - $over_ht);
            $under_gl = max(0, 100 - $over_gl);

            $gap = abs($over_ht - $over_gl);

            // Direcciones “simples” (>=50)
            $dir_global50 = ($over_gl >= 50) ? 'OVER' : 'UNDER';
            $dir_ht50     = ($over_ht >= 50) ? 'OVER' : 'UNDER';

            // Direcciones fuertes (>=70 en HT)
            $dir_strong_ht = null;
            if ($over_ht >= $minPctStrongHT)   $dir_strong_ht = 'OVER';
            if ($under_ht >= $minPctStrongHT)  $dir_strong_ht = 'UNDER';

            // Apoyo del Global en la MISMA dirección fuerte (>=60)
            $global_support_ok = false;
            if ($dir_strong_ht === 'OVER'  && $over_gl  >= $minPctSupportGlobal) $global_support_ok = true;
            if ($dir_strong_ht === 'UNDER' && $under_gl >= $minPctSupportGlobal) $global_support_ok = true;

            // Muestras mínimas
            $okGlobalAbs = $n_gl >= ($minAbsGlobal[$wName] ?? 0);
            $okHtAbs     = $n_ht >= ($minAbsHTStrong[$wName] ?? 0);

            // z-score (informativo)
            $p1 = ($n_ht > 0) ? max(0,min(1,$over_ht/100)) : 0;
            $p2 = ($n_gl > 0) ? max(0,min(1,$over_gl/100)) : 0;
            $se = ($n_ht>0 && $n_gl>0)
                ? sqrt( ($p1*(1-$p1))/max(1,$n_ht) + ($p2*(1-$p2))/max(1,$n_gl) )
                : 0.0;
            $z  = ($se>0) ? (abs($p1-$p2)/$se) : 0.0;

            // Global claro (informativo)
            $global_clear = (abs($over_gl - 50) >= $clarityPP);

            // ===== Validez de la ventana (binaria STRONG) =====
            // Reglas: muestra OK + HT fuerte (≥70) + Global apoya (≥60) en la MISMA dirección
            $valid_strong = ($okHtAbs && $okGlobalAbs && $dir_strong_ht !== null && $global_support_ok);

            $summary['results'][$mkt][$wName] = [
                'matches_filtered' => $n_ht,
                'matches_global'   => $n_gl,
                'over_filtered'    => $over_ht,
                'over_global'      => $over_gl,
                'under_filtered'   => $under_ht,
                'under_global'     => $under_gl,
                'gap_over_abs'     => $gap,
                'zscore'           => round($z, 2),

                'dir_simple_ht'    => $dir_ht50,     // >=50
                'dir_simple_gl'    => $dir_global50, // >=50
                'dir_strong_ht'    => $dir_strong_ht, // >=70 (OVER/UNDER o null)

                'ok_ht_abs'        => $okHtAbs,
                'ok_global_abs'    => $okGlobalAbs,
                'global_support_ok'=> $global_support_ok, // ≥60 en la misma dirección fuerte

                'global_clear'     => $global_clear, // informativo
                'gap_info_low'     => ($gap <= $maxGapPP), // informativo

                'valid_strong'     => $valid_strong, // <-- ESTA es la “Validez” real de la fila
            ];
        }
    }

    // ===== PASO 2: coherencia por market en una MISMA dirección fuerte =====
    foreach ($markets as $mkt) {
        if (empty($summary['results'][$mkt])) continue;

        // Contadores por dirección fuerte
        $countByDir   = ['OVER'=>0,'UNDER'=>0];
        $sumHTByDir   = ['OVER'=>0,'UNDER'=>0];
        $avgZDataByDir= ['OVER'=>[],'UNDER'=>[]];
        $trustedOkByDir = ['OVER'=>false,'UNDER'=>false];
        $bestWindowByDir = ['OVER'=>null,'UNDER'=>null];
        $bestWindowScore = ['OVER'=>-INF,'UNDER'=>-INF]; // criterio: más n_ht, luego menor z

        foreach ($windowsForCoherence as $w) {
            $r = $summary['results'][$mkt][$w] ?? null;
            if (!$r) continue;

            if ($r['valid_strong'] && $r['dir_strong_ht']) {
                $dir = $r['dir_strong_ht']; // OVER/UNDER de HT fuerte

                $countByDir[$dir]++;
                $sumHTByDir[$dir] += ($r['matches_filtered'] ?? 0);
                $avgZDataByDir[$dir][] = ($r['zscore'] ?? 0);

                if (in_array($w, $trustedWindows, true)) {
                    $trustedOkByDir[$dir] = true;
                }

                // escoger mejor ventana por n_ht y luego z más bajo
                $score = (($r['matches_filtered'] ?? 0) * 1000) - ($r['zscore'] ?? 0);
                if ($score > $bestWindowScore[$dir]) {
                    $bestWindowScore[$dir] = $score;
                    $bestWindowByDir[$dir] = $w;
                }
            }
        }

        // Elegimos dirección con más ventanas válidas fuertes
        $dirChoice = null;
        if ($countByDir['OVER'] > $countByDir['UNDER']) $dirChoice = 'OVER';
        if ($countByDir['UNDER'] > $countByDir['OVER']) $dirChoice = 'UNDER';

        if ($dirChoice && $countByDir[$dirChoice] >= $minValidWindowsSameDir) {
            if (!$needTrustedWindow || $trustedOkByDir[$dirChoice]) {
                // Promedio Z de las ventanas válidas en esa dirección
                $avgZ = 0.0;
                if (!empty($avgZDataByDir[$dirChoice])) {
                    $avgZ = array_sum($avgZDataByDir[$dirChoice]) / max(1, count($avgZDataByDir[$dirChoice]));
                }

                // Tomar datos de la “mejor ventana”
                $bestW = $bestWindowByDir[$dirChoice];
                $bestGap = null;
                if ($bestW && isset($summary['results'][$mkt][$bestW])) {
                    $bestGap = $summary['results'][$mkt][$bestW]['gap_over_abs'] ?? null;
                }

                $summary['market_recommendations'][$mkt] = [
                    'market'  => $mkt,
                    'label'   => $dirChoice,                   // OVER / UNDER
                    'score'   => $countByDir[$dirChoice],      // nº de ventanas válidas fuertes
                    'sum_ht'  => $sumHTByDir[$dirChoice],      // n_HT total de apoyo
                    'avg_z'   => round($avgZ, 2),              // menor es mejor (más estable)
                    'trusted_support' => $trustedOkByDir[$dirChoice],
                    'window_best'     => $bestW,
                    'gap_best'        => $bestGap,
                    'note'   => 'coherencia_70pct',            // etiqueta para la UI
                ];
                continue;
            }
        }

        // Si no hay coherencia suficiente, no hay recomendación para ese market
        $summary['market_recommendations'][$mkt] = null;
    }

    // ===== PASO 3: Banner (elige el mejor market) =====
    // Criterio: mayor score; empate => mayor sum_ht; empate => menor avg_z
    $best = null;
    foreach ($summary['market_recommendations'] as $mkt => $rec) {
        if (!$rec) continue;
        if (
            !$best ||
            ($rec['score'] > $best['score']) ||
            ($rec['score'] === $best['score'] && $rec['sum_ht'] > $best['sum_ht']) ||
            ($rec['score'] === $best['score'] && $rec['sum_ht'] === $best['sum_ht'] && $rec['avg_z'] < $best['avg_z'])
        ) {
            $best = $rec;
        }
    }

    if ($best) {
        $summary['recommendation'] = [
            'market'    => $best['market'],
            'label'     => $best['label'],               // OVER / UNDER
            'direction' => strtolower($best['label']),
            'score'     => $best['score'],
            'sum_ht'    => $best['sum_ht'],
            'avg_z'     => $best['avg_z'],
            'window'    => $best['window_best'],
            'gap'       => $best['gap_best'],
            'trusted_support' => $best['trusted_support'],
            'note'      => $best['note'],                // coherencia_70pct
        ];
    } else {
        $summary['recommendation'] = null;
    }

    return response()->json($summary);
}







    // Método para devolver el detalle de partidos vía AJAX
    // Método para devolver el detalle de partidos vía AJAX, incluyendo el spread
public function detail(Request $request)
{
    $period = $request->input('period'); // Puede ser 'mensual', 'semanal', 'actual' o 'global'
    $playerType = $request->input('player'); // 'p1', 'p2' o 'global'
    $player1 = $request->input('player1');
    $player2 = $request->input('player2');
    $ht1 = $request->input('ht1');
    $ht2 = $request->input('ht2');

    // Definir el rango de fechas según el período
    $actualDate = Carbon::now();
    if ($period == 'mensual') {
        $startDate = $actualDate->copy()->subDays(30)->format('Y-m-d');
    } elseif ($period == 'semanal') {
        $startDate = $actualDate->copy()->subDays(10)->format('Y-m-d');
    } elseif ($period == 'actual') {
        $startDate = $actualDate->format('Y-m-d');
    } else {
        $startDate = null;
    }

    // Construir la consulta para obtener el detalle, incluyendo el spread
    $query = DB::table('ht_results')
        ->selectRaw('player1, player2, fecha, hora, team1, team2, ft1, ft2, ht1, ht2, spread')
        ->where(function($q) use ($player1, $player2, $ht1,$ht2) {
            $q->where(function($sub) use ($player1, $player2,$ht1,$ht2) {
                $sub->where('player1', $player1)
                    ->where('ht1', $ht1)
                    ->where('player2', $player2)
                    ->where('ht2', $ht2);
            })->orWhere(function($sub) use ($player1, $player2,$ht1,$ht2) {
                $sub->where('player1', $player2)
                    ->where('ht1', $ht2)
                    ->where('player2', $player1)
                    ->where('ht2', $ht1);
            });
        });
    if ($startDate) {
        $query->whereDate('fecha', '>=', $startDate);
    }

    $details = $query->orderBy('fecha', 'desc')
                     ->orderBy('hora', 'desc')
                     ->get();

    // Retornamos una vista parcial con los detalles
    return view('bets.detail', compact('details'));
} 

  public function testing(Request $request){

    // Obtener la fecha del filtro, si se envía como parámetro GET
    $fecha = $request->get('fecha');
    $groups = DB::table('players')->selectRaw('group_p,name')->whereNotNull('group_p')->pluck('name','group_p');
    // Aquí se consulta el modelo Partido (asegúrate de tener el modelo correspondiente)
    // Puedes ajustar la consulta según tu lógica: si $fecha está definida se filtra, si no, se pueden mostrar todos o ninguno.
    $partidos = DB::table('ht_results')->when($fecha, function ($query, $fecha) {
        return $query->whereDate('fecha', $fecha);
    });

    $playerIds = $partidos->pluck('p1')
                  ->merge($partidos->pluck('p2'))
                  ->unique()
                  ->toArray();

    // 3) Recupera sólo los grupos de esos jugadores
    $groups = DB::table('players')
        ->whereIn('id', $playerIds)
        ->whereNotNull('group_p')
        ->distinct()
        ->pluck('name', 'group_p');  // [ group_p => name ]


    if(isset($request->groups)){
      $playerIds = DB::table('players')->whereIn('group_p', $request->groups)->get()->pluck('id')->toArray();
      $partidos = $partidos->where(function($q) use ($playerIds) {
          $q->whereIn('p1', $playerIds)
            ->orWhereIn('p2', $playerIds);
      });
    }

    $partidos = $partidos->get();

    $type = $request->type;

    if($type == "1"){
      return view('bets.test_completed', compact('partidos','groups'));
    }

    return view('bets.test', compact('partidos'));
  }

  public function shiny_data(Request $request){
        $player1 = $request->input('player1');
        $player2 = $request->input('player2');
        $ht1      = $request->input('ht1');
        $ht2      = $request->input('ht2');
        $market   = $request->input('market');

        // Fechas
        $today = Carbon::today();
        $d_3   = $today->copy()->subDays(3)->toDateString();
        $d_7   = $today->copy()->subDays(7)->toDateString();
        $d_14  = $today->copy()->subDays(14)->toDateString();
        $d_30  = $today->copy()->subDays(30)->toDateString();

        $today = $today->format('Y-m-d');
        #$today = "2025-06-19";

        // Mapping de columnas según mercado
        $marketMapping = [
            '0.5' => ['ft' => 'porcentaje_zero', 'sp' => 'porcentaje_spzero'],
            '1.5' => ['ft' => 'porcentaje_one',  'sp' => 'porcentaje_spone'],
            '2.5' => ['ft' => 'porcentaje_two',  'sp' => 'porcentaje_sptwo'],
            '3.5' => ['ft' => 'porcentaje_tree', 'sp' => 'porcentaje_sptree'],
            '4.5' => ['ft' => 'porcentaje_four', 'sp' => 'porcentaje_spfour'],
            '5.5' => ['ft' => 'porcentaje_five', 'sp' => 'porcentaje_spfive'],
            '6.5' => ['ft' => 'porcentaje_six',  'sp' => 'porcentaje_spsix'],
        ];

        $ftColumn = $marketMapping[$market]['ft'] ?? null;
        $spColumn = $marketMapping[$market]['sp'] ?? null;

        // Últimos 5 para player1
        $matches_a = DB::table('ht_results')
            ->selectRaw(implode(',', [
                'player1',
                'player2',
                'fecha',
                'hora',
                'team1',
                'team2',
                'ht1',
                'ht2',
                '(ht1 + ht2) AS htt',
                'ft1',
                'ft2',
                '(ft1 + ft2) AS ft_total',
                'spread as sp'
            ]))
            ->where(function($q) use ($player1) {
                $q->where('player1', $player1)
                  ->orWhere('player2', $player1);
            })
            ->whereDate('fecha', '>=', $today)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->limit(25)
            ->get();

        // Últimos 5 para player2
        $matches_b = DB::table('ht_results')
            ->selectRaw(implode(',', [
                'player1',
                'player2',
                'fecha',
                'hora',
                'team1',
                'team2',
                'ht1',
                'ht2',
                '(ht1 + ht2) AS htt',
                'ft1',
                'ft2',
                '(ft1 + ft2) AS ft_total',
                'spread as sp'
            ]))
            ->where(function($q) use ($player2) {
                $q->where('player1', $player2)
                  ->orWhere('player2', $player2);
            })
            ->whereDate('fecha', '>=', $today)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->limit(25)
            ->get();

        // Datos filtrados y generales usando tu helper get_data_vs(...)
        $data_0_fil   = $this->get_data_vs($ht1, $ht2, $today, $player1, $player2)[0] ?? null;
        $data_3_fil   = $this->get_data_vs($ht1, $ht2, $d_3, $player1, $player2)[0] ?? null;
        $data_7_fil   = $this->get_data_vs($ht1, $ht2, $d_7, $player1, $player2)[0] ?? null;
        $data_14_fil  = $this->get_data_vs($ht1, $ht2, $d_14, $player1, $player2)[0] ?? null;
        $data_30_fil  = $this->get_data_vs($ht1, $ht2, $d_30, $player1, $player2)[0] ?? null;
        $data_all_fil = $this->get_data_vs($ht1, $ht2, null,  $player1, $player2)[0] ?? null;

        $data_0_global = $this->get_data_vs(null,  null, $today, $player1, $player2)[0] ?? null;
        $data_3_global = $this->get_data_vs(null,  null, $d_3, $player1, $player2)[0] ?? null;
        $data_7_global = $this->get_data_vs(null,  null, $d_7, $player1, $player2)[0] ?? null;
        $data_14_global = $this->get_data_vs(null,  null, $d_14, $player1, $player2)[0] ?? null;
        $data_30_global = $this->get_data_vs(null,  null, $d_30, $player1, $player2)[0] ?? null;
        $data_all_global = $this->get_data_vs(null,  null, null, $player1, $player2)[0] ?? null;

        // Ejemplo de métricas a 60 días (aquí reemplaza 170 por tus funciones reales)
        $spTeamA60 = 170;
        $ftTeamA60 = 170;
        $spTeamB60 = 170;
        $ftTeamB60 = 170;
        $spColumn = $marketMapping['2.5']['sp'] ?? null;
        $htt = $ht1+$ht2;



        $today_data = [
          "matches_filtered" => $data_0_fil->partidos,
          "matches_global" => $data_0_global->partidos,
          "zero_filtered" => $data_0_fil->spzero,
          "zero_global" => $data_0_global->spzero,
          "one_filtered" => $data_0_fil->spone,
          "one_global" => $data_0_global->spone,
          "two_filtered" => $data_0_fil->sptwo,
          "two_global" => $data_0_global->sptwo,
          "tree_filtered" => $data_0_fil->sptree,
          "tree_global" => $data_0_global->sptree,
          "four_filtered" => $data_0_fil->spfour,
          "four_global" => $data_0_global->spfour,
          "market_over_filtered" => round($data_0_fil->{$ftColumn}),
          "market_over_global" => round($data_0_global->{$ftColumn}),
          "market_under_filtered" => round(100 - $data_0_fil->{$ftColumn}),
          "market_under_global" => round(100 - $data_0_global->{$ftColumn})
        ];

        $three_data = [
          "matches_filtered" => $data_3_fil->partidos,
          "matches_global" => $data_3_global->partidos,
          "zero_filtered" => $data_3_fil->spzero,
          "zero_global" => $data_3_global->spzero,
          "one_filtered" => $data_3_fil->spone,
          "one_global" => $data_3_global->spone,
          "two_filtered" => $data_3_fil->sptwo,
          "two_global" => $data_3_global->sptwo,
          "tree_filtered" => $data_3_fil->sptree,
          "tree_global" => $data_3_global->sptree,
          "four_filtered" => $data_3_fil->spfour,
          "four_global" => $data_3_global->spfour,
          "market_over_filtered" => round($data_3_fil->{$ftColumn}),
          "market_over_global" => round($data_3_global->{$ftColumn}),
          "market_under_filtered" => round(100 - $data_3_fil->{$ftColumn}),
          "market_under_global" => round(100 - $data_3_global->{$ftColumn})
        ];

        $week_data = [
          "matches_filtered" => $data_7_fil->partidos,
          "matches_global" => $data_7_global->partidos,
          "zero_filtered" => $data_7_fil->spzero,
          "zero_global" => $data_7_global->spzero,
          "one_filtered" => $data_7_fil->spone,
          "one_global" => $data_7_global->spone,
          "two_filtered" => $data_7_fil->sptwo,
          "two_global" => $data_7_global->sptwo,
          "tree_filtered" => $data_7_fil->sptree,
          "tree_global" => $data_7_global->sptree,
          "four_filtered" => $data_7_fil->spfour,
          "four_global" => $data_7_global->spfour,
          "market_over_filtered" => round($data_7_fil->{$ftColumn}),
          "market_over_global" => round($data_7_global->{$ftColumn}),
          "market_under_filtered" => round(100 - $data_7_fil->{$ftColumn}),
          "market_under_global" => round(100 - $data_7_global->{$ftColumn})
        ];

        $two_week_data = [
          "matches_filtered" => $data_14_fil->partidos,
          "matches_global" => $data_14_global->partidos,
          "zero_filtered" => $data_14_fil->spzero,
          "zero_global" => $data_14_global->spzero,
          "one_filtered" => $data_14_fil->spone,
          "one_global" => $data_14_global->spone,
          "two_filtered" => $data_14_fil->sptwo,
          "two_global" => $data_14_global->sptwo,
          "tree_filtered" => $data_14_fil->sptree,
          "tree_global" => $data_14_global->sptree,
          "four_filtered" => $data_14_fil->spfour,
          "four_global" => $data_14_global->spfour,
          "market_over_filtered" => round($data_14_fil->{$ftColumn}),
          "market_over_global" => round($data_14_global->{$ftColumn}),
          "market_under_filtered" => round(100 - $data_14_fil->{$ftColumn}),
          "market_under_global" => round(100 - $data_14_global->{$ftColumn})
        ];

        $month_data = [
          "matches_filtered" => $data_30_fil->partidos,
          "matches_global" => $data_30_global->partidos,
          "zero_filtered" => $data_30_fil->spzero,
          "zero_global" => $data_30_global->spzero,
          "one_filtered" => $data_30_fil->spone,
          "one_global" => $data_30_global->spone,
          "two_filtered" => $data_30_fil->sptwo,
          "two_global" => $data_30_global->sptwo,
          "tree_filtered" => $data_30_fil->sptree,
          "tree_global" => $data_30_global->sptree,
          "four_filtered" => $data_30_fil->spfour,
          "four_global" => $data_30_global->spfour,
          "market_over_filtered" => round($data_30_fil->{$ftColumn}),
          "market_over_global" => round($data_30_global->{$ftColumn}),
          "market_under_filtered" => round(100 - $data_30_fil->{$ftColumn}),
          "market_under_global" => round(100 - $data_30_global->{$ftColumn})
        ];

        $all_data = [
          "matches_filtered" => $data_all_fil->partidos,
          "matches_global" => $data_all_global->partidos,
          "zero_filtered" => $data_all_fil->spzero,
          "zero_global" => $data_all_global->spzero,
          "one_filtered" => $data_all_fil->spone,
          "one_global" => $data_all_global->spone,
          "two_filtered" => $data_all_fil->sptwo,
          "two_global" => $data_all_global->sptwo,
          "tree_filtered" => $data_all_fil->sptree,
          "tree_global" => $data_all_global->sptree,
          "four_filtered" => $data_all_fil->spfour,
          "four_global" => $data_all_global->spfour,
          "market_over_filtered" => round($data_all_fil->{$ftColumn}),
          "market_over_global" => round($data_all_global->{$ftColumn}),
          "market_under_filtered" => round(100 - $data_all_fil->{$ftColumn}),
          "market_under_global" => round(100 - $data_all_global->{$ftColumn})
        ];

        $response = array(
          "player1" => $player1,
          "player2" => $player2,
          "today_data" => $today_data,
          "three_data" => $three_data,
          "week_data" => $week_data,
          "two_week_data" => $two_week_data,
          "month_data" => $month_data,
          "all_data" => $all_data,
          'last5A'         => $matches_a,
          'last5B'         => $matches_b,
        );

        return response()->json($response);

        /*
        return response()->json([
            'teamA'          => $player1,
            'teamB'          => $player2,
            'last5A'         => $matches_a,
            'last5B'         => $matches_b,
            #'DayftFiltered'  => optional($dayData)->{$ftColumn},
            #'DayspFiltered'  => optional($dayData)->{$spColumn},
            'ftFiltered'     => optional($data_all_fil)->{$ftColumn},
            #'spFiltered'     => optional($allDays)->{$spColumn},
            'ftGeneral'      => optional($data_all_global)->{$ftColumn},
            #'spGeneral'      => optional($generalAll)->{$spColumn},
            'sp_teamA_60'    => $spTeamA60,
            'ft_teamA_60'    => $ftTeamA60,
            'sp_teamB_60'    => $spTeamB60,
            'ft_teamB_60'    => $ftTeamB60,
            // Si luego agregas moda, haz lo mismo con tu helper:
            //'mode0d'       => …,
            //'mode7d'       => …,
            //'mode14d'      => …,
            //'mode30d'      => …,
        ]);
        */
  }

  public function process_data(Request $request){
    // Se obtienen los parámetros enviados
    $player1 = $request->input('player1');
    $player2 = $request->input('player2');
    $ht1 = $request->input('ht1'); // goles HT para el player1
    $ht2 = $request->input('ht2'); // goles HT para el player2

    $actualDate = Carbon::now();
    $d_init   = $actualDate->copy()->subDays(90)->format('Y-m-d');
    $actual = $request->input('fecha');
       
    $promedio_p1 = DB::table('ht_results')
                  ->where(function($query) use ($player1) {
                      $query->where('player1', $player1)
                            ->orWhere('player2', $player1);
                  })
                  ->selectRaw("COUNT(*) as partidos_jugados,
                      AVG(CASE 
                            WHEN player1 = ? THEN ft1 
                            WHEN player2 = ? THEN ft2 
                          END) as promedio_ft,
                      AVG(CASE 
                            WHEN player1 = ? THEN ht1 
                            WHEN player2 = ? THEN ht2 
                          END) as promedio_ht
                  ", [$player1, $player1, $player1, $player1]);

    $promedio_p2 = DB::table('ht_results')
                ->where(function($query) use ($player2) {
                    $query->where('player1', $player2)
                          ->orWhere('player2', $player2);
                })
                ->selectRaw("COUNT(*) as partidos_jugados,
                    AVG(CASE 
                          WHEN player1 = ? THEN ft1 
                          WHEN player2 = ? THEN ft2 
                        END) as promedio_ft,
                    AVG(CASE 
                          WHEN player1 = ? THEN ht1 
                          WHEN player2 = ? THEN ht2 
                        END) as promedio_ht
                ", [$player2, $player2, $player2, $player2]);

    $promedio_vs = DB::table('ht_results')
                    ->where(function($query) use ($player1, $player2) {
                        $query->where(function($q) use ($player1, $player2) {
                            $q->where('player1', $player1)
                              ->where('player2', $player2);
                        })->orWhere(function($q) use ($player1, $player2) {
                            $q->where('player1', $player2)
                              ->where('player2', $player1);
                        });
                    })
                    ->selectRaw("COUNT(*) as partidos_jugados,
                        AVG(CASE 
                              WHEN player1 = ? THEN ft1 
                              WHEN player2 = ? THEN ft2 
                            END) as total_ft,
                        AVG(CASE 
                              WHEN player1 = ? THEN ht1 
                              WHEN player2 = ? THEN ht2 
                            END) as total_ht,
                        AVG(CASE 
                              WHEN player1 = ? THEN ft1 
                              WHEN player2 = ? THEN ft2 
                            END) as total_ft_opp,
                        AVG(CASE 
                              WHEN player1 = ? THEN ht1 
                              WHEN player2 = ? THEN ht2 
                            END) as total_ht_opp
                    ", [
                         $player1, $player1,      // Para total_ft
                         $player1, $player1,      // Para total_ht
                         $player2, $player2,    // Para total_ft_opp
                         $player2, $player2     // Para total_ht_opp
                    ]);

    $promedios = [
      "x_p1_hist_ft" => $promedio_p1->first()->promedio_ft,
      "x_p1_hist_ht" => $promedio_p1->first()->promedio_ht,
      "x_p1_hist_sp" => $promedio_p1->first()->promedio_ft - $promedio_p1->first()->promedio_ht,
      "x_p1_d_ft" => $promedio_p1->whereDate('fecha',$actual)->first()->promedio_ft,
      "x_p1_d_ht" => $promedio_p1->whereDate('fecha',$actual)->first()->promedio_ht,

      "x_p2_hist_ft" => $promedio_p2->first()->promedio_ft,
      "x_p2_hist_ht" => $promedio_p2->first()->promedio_ht,
      "x_p2_hist_sp" => $promedio_p2->first()->promedio_ft - $promedio_p2->first()->promedio_ht,
      "x_p2_d_ft" => $promedio_p2->whereDate('fecha',$actual)->first()->promedio_ft,
      "x_p2_d_ht" => $promedio_p2->whereDate('fecha',$actual)->first()->promedio_ht,

      "x_p1_hist_ft_vs" => $promedio_vs->first()->total_ft,
      "x_p1_hist_ht_vs" => $promedio_vs->first()->total_ht,
      "x_p1_hist_sp_vs" => $promedio_vs->first()->total_ft - $promedio_vs->first()->total_ht,
      "x_p1_d_ft_vs" => $promedio_vs->whereDate('fecha',$actual)->first()->total_ft,
      "x_p1_d_ht_vs" => $promedio_vs->whereDate('fecha',$actual)->first()->total_ht,

      "x_p2_hist_ft_vs" => $promedio_vs->first()->total_ft_opp,
      "x_p2_hist_ht_vs" => $promedio_vs->first()->total_ht_opp,
      "x_p2_hist_sp_vs" => $promedio_vs->first()->total_ft_opp - $promedio_vs->first()->total_ht_opp,
      "x_p2_d_ft_vs" => $promedio_vs->whereDate('fecha',$actual)->first()->total_ft_opp,
      "x_p2_d_ht_vs" => $promedio_vs->whereDate('fecha',$actual)->first()->total_ht_opp,
    ];

      $marketMapping = [
        '0.5' => ['ft' => 'porcentaje_zero', 'sp' => 'porcentaje_spzero'],
        '1.5' => ['ft' => 'porcentaje_one',  'sp' => 'porcentaje_spone'],
        '2.5' => ['ft' => 'porcentaje_two',  'sp' => 'porcentaje_sptwo'],
        '3.5' => ['ft' => 'porcentaje_tree', 'sp' => 'porcentaje_sptree'],
        '4.5' => ['ft' => 'porcentaje_four', 'sp' => 'porcentaje_spfour'],
        '5.5' => ['ft' => 'porcentaje_five', 'sp' => 'porcentaje_spfive'],
        '6.5' => ['ft' => 'porcentaje_six',  'sp' => 'porcentaje_spsix'],
      ];

      $ftColumn = $marketMapping[$request->market]['ft'] ?? null;
      $spColumn = $marketMapping['2.5']['sp'] ?? null;
      $test = @$this->get_data_vs(null,null,null,$player1,$player2)['0']->$spColumn;
      $markets = array(
        "market_hist_vs_ft" => @$this->get_data_vs(null,null,null,$player1,$player2)['0']->$ftColumn,
        "market_hist_vs_sp" => @$this->get_data_vs(null,null,null,$player1,$player2)['0']->$spColumn,
        "market_dia_vs_ft" => @$this->get_data_vs(null,null,$actual,$player1,$player2)['0']->$ftColumn,
        "market_dia_vs_sp" => @$this->get_data_vs(null,null,$actual,$player1,$player2)['0']->$spColumn,
        "market_hist_vs_ft_filtered_ht" => @$this->get_data_vs($ht1,$ht2,null,$player1,$player2)[0]->$ftColumn,
        "market_hist_vs_sp_filtered_ht" => @$this->get_data_vs($ht1,$ht2,null,$player1,$player2)[0]->$spColumn,
        "market_dia_vs_ft_filtered_ht" => @$this->get_data_vs($ht1,$ht2,$actual,$player1,$player2)[0]->$ftColumn,
        "market_dia_vs_sp_filtered_ht" => @$this->get_data_vs($ht1,$ht2,$actual,$player1,$player2)[0]->$spColumn,
      );

      #$test2 = $this->get_data_complement($ht1,$ht2,$actual,$player1,$player2,"extremos");
      #$test3 = $this->get_data_complement(null,null,$actual,$player1,$player2,"remontada");
      $remontada = array(
        "remontada_his_n" => $this->get_data_complement(null,null,null,$player1,$player2,"remontada")[0]->total_remontadas,
        "remontada_his_por" => $this->get_data_complement(null,null,null,$player1,$player2,"remontada")[0]->porcentaje_remontadas,
        "remontada_his_d_n" => $this->get_data_complement(null,null,$actual,$player1,$player2,"remontada")[0]->total_remontadas,
        "remontada_his_d_por" => $this->get_data_complement(null,null,$actual,$player1,$player2,"remontada")[0]->porcentaje_remontadas,
        "remontada_his_n_f" => $this->get_data_complement($ht1,$ht2,null,$player1,$player2,"remontada")[0]->total_remontadas,
        "remontada_his_por_f" => $this->get_data_complement($ht1,$ht2,null,$player1,$player2,"remontada")[0]->porcentaje_remontadas,
        "remontada_his_d_n_f" => $this->get_data_complement($ht1,$ht2,$actual,$player1,$player2,"remontada")[0]->total_remontadas,
        "remontada_his_d_por_f" => $this->get_data_complement($ht1,$ht2,$actual,$player1,$player2,"remontada")[0]->porcentaje_remontadas,
      );

      $extremos = array(

      );

      return json_encode(["promedios" => $promedios, "market" => $markets, "remontada" => $remontada, "extremos"]);

      dd($complements);
    
  }

  private function get_data_complement($ht1 = null,$ht2 = null,$date = null,$player1,$player2, $type = null){

    $data = DB::table('ht_results')
        ->where(function($query) use ($player1, $player2, $ht1,$ht2) {
            $query->where(function($q) use ($player1, $player2, $ht1,$ht2) {
                $q->where('player1', $player1)
                  ->where('player2', $player2);

                  if ($ht1) {
                    $q->where('ht1',$ht1);
                    $q->where('ht2',$ht2);
                  }

            })->orWhere(function($q) use ($player1, $player2, $ht1,$ht2) {
                $q->where('player1', $player2)
                  ->where('player2', $player1);

                  if ($ht1) {
                    $q->where('ht1',$ht2);
                    $q->where('ht2',$ht1);
                  }
            });
        });

    if($type == "extremos"){
      $data = $data->selectRaw('COUNT(*) as partidos_jugados,
            SUM(case when (ft1+ft2 < 3) then 1 else 0 end) as lower,
            (SUM(case when (ft1+ft2 < 3) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as por_lower,
            SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as upper,
            (SUM(case when (ft1+ft2 > 6) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as por_upper
        ');
    }

    if($type == "remontada"){
      $data = $data->selectRaw('
        COUNT(*) as total_partidos,
        SUM(
          CASE 
            WHEN (player1 = ? AND ht1 < ht2 AND ft1 > ft2) 
              OR (player2 = ? AND ht2 < ht1 AND ft2 > ft1)
            THEN 1 ELSE 0 
          END
        ) as total_remontadas,
        (SUM(
          CASE 
            WHEN (player1 = ? AND ht1 < ht2 AND ft1 > ft2) 
              OR (player2 = ? AND ht2 < ht1 AND ft2 > ft1)
            THEN 1 ELSE 0 
          END
        ) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_remontadas
    ', [$player1, $player2, $player2, $player1]);
    }

    $date ? $data->where('fecha','<=', $date) : "";
    
    return $data->get();
  }

  private function get_data_vs($ht1 = null,$ht2 = null,$date = null,$player1,$player2){

    $market_vs = DB::table('ht_results')
        ->where(function($query) use ($player1, $player2, $ht1,$ht2) {
            $query->where(function($q) use ($player1, $player2, $ht1,$ht2) {
                $q->where('player1', $player1)
                  ->where('player2', $player2);

                  if ($ht1 || $ht2) {
                    $q->where('ht1',$ht1);
                    $q->where('ht2',$ht2);
                  }

            })->orWhere(function($q) use ($player1, $player2, $ht1,$ht2) {
                $q->where('player1', $player2)
                  ->where('player2', $player1);

                  if ($ht1 || $ht2) {
                    $q->where('ht1',$ht2);
                    $q->where('ht2',$ht1);
                  }
            });
        })
        ->selectRaw('
            player1, player2, fecha, hora, team1, team2,
            ft1, ft2, ht1, ht2,
            SUM(case when (ft1+ft2 > 0) then 1 else 0 end) as zero,
            (SUM(case when (ft1+ft2 > 0) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_zero,
            SUM(case when (ft1+ft2 > 1) then 1 else 0 end) as one,
            (SUM(case when (ft1+ft2 > 1) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_one,
            SUM(case when (ft1+ft2 > 2) then 1 else 0 end) as two,
            (SUM(case when (ft1+ft2 > 2) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_two,
            SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as tree,
            (SUM(case when (ft1+ft2 > 3) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_tree,
            SUM(case when (ft1+ft2 > 3) then 1 else 0 end) as four,
            (SUM(case when (ft1+ft2 > 3) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_four,
            SUM(case when (ft1+ft2 > 5) then 1 else 0 end) as five,
            (SUM(case when (ft1+ft2 > 5) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_five,
            SUM(case when (ft1+ft2 > 6) then 1 else 0 end) as six,
            (SUM(case when (ft1+ft2 > 6) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_six,
            SUM(case when (spread = 0) then 1 else 0 end) as spzero,
            (SUM(case when (spread = 0) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_spzero,
            SUM(case when (spread = 1) then 1 else 0 end) as spone,
            (SUM(case when (spread = 1) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_spone,
            SUM(case when (spread = 2) then 1 else 0 end) as sptwo,
            (SUM(case when (spread = 2) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_sptwo,
            SUM(case when (spread = 3) then 1 else 0 end) as sptree,
            (SUM(case when (spread = 3) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_sptree,
            SUM(case when (spread > 3) then 1 else 0 end) as spfour,
            (SUM(case when (spread > 3) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_spfour,
            SUM(case when (spread > 5) then 1 else 0 end) as spfive,
            (SUM(case when (spread > 5) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_spfive,
            SUM(case when (spread > 6) then 1 else 0 end) as spsix,
            (SUM(case when (spread > 6) then 1 else 0 end) / NULLIF(COUNT(*), 0)) * 100 as porcentaje_spsix,
            COUNT(*) AS partidos
        ');

        $date ? $market_vs->where('fecha','>',$date) : "";
        // Por ejemplo, si se pasa 1.5, el umbral es 1 (1.5 - 0.5)
        #$threshold = $market - 0.5;
        #$market ?  $market_vs->whereRaw('(ft1 + ft2) > ?', [$threshold]) : "";

        return $market_vs->get();
  }


    public function import_bot_bet(){
      $archivo = 'temporal.txt';
      $texto = file_get_contents($archivo); 
      $data  = explode("Players", $texto);
      $dxx = [];

      foreach ($data as $key => $value) {
        if($key > 0){
          $dx  = explode("\n", $value);
          $ln1 = explode(" ", $dx[0]);
          $ln2 = explode(" ", $dx[1]);
          $ln3 = explode(" ", $dx[2]);
          $team1 = $ln1[1];
          $player1 = strpos($ln1[2], "(") === 0 ? strtoupper($ln1[2]) : strtoupper($ln1[3]);
          $team2   = $ln2[0];
          $player2 = strpos($ln2[1], "(") === 0 ? strtoupper($ln2[1]) : strtoupper($ln2[2]);
          $f = explode("/", $ln3[0]);
          $fecha = $f[2]."-".$f[1]."-".$f[0];
          $hora = $ln3[1];
          $avg = "";
          $expected_v = "";
          $expected_l = "";
          $finals1 = $ln3[3];
          $finals2 = $ln3[5];

          $count_ft  = (int)$finals1+(int)$finals2;
          $status = (float)$count_ft > 2 ? 1 : 0 ;
          $status_2 = (float)$count_ft > 1 ? 1 : 0 ; #market1.5
          $status_3 = (float)$count_ft > 3 ? 1 : 0 ; #market3.5
          $status_4 = (float)$count_ft > 0 ? 1 : 0 ; #market0.5

          $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;

          $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;

          if(empty($p1)){
            $p1_data = DB::table('players')->insert(['name' => $player1]);
            $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;
          }

          if(empty($p2)){
            $p2_data = DB::table('players')->insert(['name' => $player2]);
            $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;
          }

          $temp = [
            "team1" => $team1,
            "player1" => $p1,
            "team2" => $team2,
            "player2" => $p2,
            "fecha" => $fecha,
            "hora" => $hora,
            "avg" => $avg,
            "finals1" => $finals1,
            "finals2" => $finals2,
            "expected_l" => $expected_l,
            "expected_v" => $expected_v,
            "status_avg" => $status,
          ];

          if($finals1 != "" && $finals2 != ""){
            $validate = Bets_temp::where("player1", $p1)->where("player2", $p2)->where("fecha", $fecha)->where("hora", $hora)->first();

            empty($validate)? Bets_temp::create($temp) : "";

            array_push($dxx, $temp);
          }

        }
      }

      return json_encode(["datos" => $dxx]);
    }

    public function import_manual_bet(){
      $archivo = 'text.txt';
      $texto = file_get_contents($archivo); 
      $data  = explode("Players", $texto);
      $dxx = [];

      foreach ($data as $key => $value) {
        if($key > 0){
          $dx  = explode("\n", $value);
          $ln1 = explode(" ", $dx[0]);
          $ln2 = explode(" ", $dx[1]);
          $ln3 = explode(" ", $dx[2]);
          $team1 = $ln1[1];
          $player1 = strpos($ln1[2], "(") === 0 ? strtoupper($ln1[2]) : strtoupper($ln1[3]);
          $team2   = $ln2[0];
          $player2 = strpos($ln2[1], "(") === 0 ? strtoupper($ln2[1]) : strtoupper($ln2[2]);
          $f = explode("/", $ln3[0]);
          $fecha = $f[2]."-".$f[1]."-".$f[0];
          $hora = $ln3[1];
          $avg = $ln3[9];
          $expected_v = $ln3[11];
          $expected_l = $ln3[13];
          $finals1 = $ln3[3];
          $finals2 = $ln3[5];

          $count_ft  = (int)$finals1+(int)$finals2;
          $status = (float)$count_ft > 2 ? 1 : 0 ;
          $status_2 = (float)$count_ft > 1 ? 1 : 0 ; #market1.5
          $status_3 = (float)$count_ft > 3 ? 1 : 0 ; #market3.5
          $status_4 = (float)$count_ft > 0 ? 1 : 0 ; #market0.5

          $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;

          $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;

          if(empty($p1)){
            $p1_data = DB::table('players')->insert(['name' => $player1]);
            $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;
          }

          if(empty($p2)){
            $p2_data = DB::table('players')->insert(['name' => $player2]);
            $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;
          }

          $temp = [
            "team1" => $team1,
            "player1" => $p1,
            "team2" => $team2,
            "player2" => $p2,
            "fecha" => $fecha,
            "hora" => $hora,
            "avg" => $avg,
            "finals1" => $finals1,
            "finals2" => $finals2,
            "expected_l" => $expected_l,
            "expected_v" => $expected_v,
            "status_avg" => $status,
            "status_0" => str_replace("\r", "", $status_4),
            "status_1" => str_replace("\r", "", $status_2),
            "status_3" => str_replace("\r", "", $status_3),
          ];

          if($finals1 != "" && $finals2 != ""){
            $validate = Bets::where("player1", $p1)->where("player2", $p2)->where("fecha", $fecha)->where("hora", $hora)->first();

            empty($validate)? Bets::create($temp) : "";

            array_push($dxx, $temp);
          }

        }
      }



    }

    public function sync(Request $request)
    {
        $request->validate([
            'player1' => 'required|integer',
            'player2' => 'required|integer',
        ]);

        $p1 = $request->player1;
        $p2 = $request->player2;

        $player1 = @Player::where('id', $p1)->first()->id_externo;
        $player2 = @Player::where('id', $p2)->first()->id_externo;

        // IP pública del túnel (desde config o env)
        $baseUrl = "http://127.0.0.1:8000";
        // ej: http://X.X.X.X:8000
        $url = $baseUrl . "/scrape-h2h?player1={$player1}&player2={$player2}";

        if(empty($player1) || empty($player2)){
          return response()->json([
            'message' => 'Error faltan ids, '.$url,
          ]);
        }

        

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 120,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false || $httpCode !== 200) {
            return response()->json([
                'message' => 'Error ejecutando scraper',
                'error' => curl_error($ch)
            ], 500);
        }

        curl_close($ch);

        return response()->json([
            'message' => 'H2H sincronizado correctamente',
            'query' => $url,
            'data' => json_decode($response, true)
        ]);
    }


    /**
     * Endpoint para gatillar la descarga de la API manualmente
     */
    public function sync_betsapi(Request $request)
    {
        // Se puede enviar una fecha específica por GET (?date=2026-03-09), de lo contrario usa hoy
        $date = $request->input('date', null); 
        
        try {
            $inserted = BetsApiService::syncEndedEvents($date);
            
            return response()->json([
                'status' => 'success',
                'message' => "Sincronización completada. Se insertaron {$inserted} registros nuevos.",
                'date_processed' => $date ?: date('Y-m-d')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al sincronizar con BetsAPI: ' . $e->getMessage()
            ], 500);
        }
    }


    /*
    public function import_manual_bet(){

      $archivo = 'text.txt';
      $texto = file_get_contents($archivo); 
      $data  = explode("Eb8", $texto);
      $dxx = [];

      foreach ($data as $key => $value) {
        if($key > 0){
          $dx = explode("\n", $value);
          $team1 = explode(" ", $dx[2])[0];
          $team2 = explode(" ", $dx[3])[0];
          $player1 = strtoupper(count(explode(" ", $dx[2])) == 2 ? explode(" ", $dx[2])[1]: explode(" ", $dx[2])[2]);
          $player2 = strtoupper(count(explode(" ", $dx[3])) == 2 ? explode(" ", $dx[3])[1]: explode(" ", $dx[3])[2]);
          $player1 = str_replace("\r", "", $player1);
          $player2 = str_replace("\r", "", $player2);
          $fecha = @explode(" ", $dx[4])[0];
          $hora = @explode(" ", $dx[4])[1];
          $avg = @explode("- ", $dx[5])[1];
          $avg = substr($avg, 1);
          $ = @explode(" - ", $dx[5])[1][0];
          $longitud = @strlen(explode(" - ", $dx[5])[0]);
          $expected_l = @substr(explode(" - ", $dx[5])[0], $longitud - 1, 1);
          $efes = @explode(" - ", $dx[5]);
          $efes = @explode(" ", $efes[1]);
          $finals1 = $efes[2];
          $finals2 = @explode(" - ", $dx[5])[2];
          
         

          $count_ft  = (int)$finals1+(int)$finals2;
          $status = (float)$count_ft > 2 ? 1 : 0 ;
          $status_2 = (float)$count_ft > 1 ? 1 : 0 ; #market1.5
          $status_3 = (float)$count_ft > 3 ? 1 : 0 ; #market3.5
          $status_4 = (float)$count_ft > 0 ? 1 : 0 ; #market0.5

          $f = explode("/", $fecha);
          $fecha = $f[2]."-".$f[1]."-".$f[0];

          #$players = DB::table('players')->selectRaw('id,name')->pluck('id','name'); 
          $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;

          $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;

          if(empty($p1)){
            $p1_data = DB::table('players')->insert(['name' => $player1]);
            $p1 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player1.'%')->first()->id;
          }

          if(empty($p2)){
            $p2_data = DB::table('players')->insert(['name' => $player2]);
            $p2 = @DB::table('players')->selectRaw('id,name')->where('name','like','%'.$player2.'%')->first()->id;
          }


          $temp = [
            "team1" => $team1,
            "player1" => $p1,
            "team2" => $team2,
            "player2" => $p2,
            "fecha" => $fecha,
            "hora" => $hora,
            "avg" => $avg,
            "finals1" => $finals1,
            "finals2" => $finals2,
            "expected_l" => $expected_l,
            "expected_v" => $expected_v,
            "status_avg" => $status,
            "status_0" => str_replace("\r", "", $status_4),
            "status_1" => str_replace("\r", "", $status_2),
            "status_3" => str_replace("\r", "", $status_3),
          ];

          if($finals1 != "" && $finals2 != ""){
            $validate = Bets::where("player1", $p1)->where("player2", $p2)->where("fecha", $fecha)->where("hora", $hora)->first();

            empty($validate)? Bets::create($temp) : "";

            array_push($dxx, $temp);
          }
        }
        }


      return json_encode(["datos" => $dxx]);
    }
    */
    
}


/*
delete from players where id in (178, 183, 184,155,154,143, 144, 146, 149,204, 207, 209, 212,150, 153,157, 158, 201,169, 208, 211, 215,123,165, 166, 171, 173,168, 170,142, 145,182, 185,151, 152, 156, 159, 195, 196, 200,137, 138, 141, 172, 175, 181,139, 140,198,173, 174,189,10,147, 148,177,134,203);
UPDATE bets SET player1 = 8   where player1 in (178, 183, 184);
UPDATE bets SET player1 = 26   where player1 in (155);
UPDATE bets SET player2 = 24  where player2 in (155);
UPDATE bets SET player2 = 23  where player2 in (143, 144, 146, 149);
UPDATE bets SET player2 = 58  where player2 in (204, 207, 209, 212);
UPDATE bets SET player2 = 40  where player2 in (150, 153);
UPDATE bets SET player2 = 38  where player2 in (157, 158, 201);
UPDATE bets SET player2 = 127  where player2 in (169, 208, 211, 215);
UPDATE bets SET player2 = 15  where player2 in (123);
UPDATE bets SET player2 = 6  where player2 in (165, 166, 171, 173);
UPDATE bets SET player2 = 62  where player2 in ( 168, 170);
UPDATE bets SET player2 = 29  where player2 in (142, 145);
UPDATE bets SET player2 = 5  where player2 in (182, 185);
UPDATE bets SET player2 = 42  where player2 in (151, 152, 156, 159, 195, 196, 200);
UPDATE bets SET player2 = 4  where player2 in (137, 138, 141, 172, 175, 181);
UPDATE bets SET player2 = 14  where player2 in (139, 140);
UPDATE bets SET player2 = 21  where player2 in (198);
UPDATE bets SET player2 = 3  where player2 in (173, 174);
UPDATE bets SET player2 = 30  where player2 in (189);
UPDATE bets SET player2 = 9  where player2 in (10);
UPDATE bets SET player2 = 41  where player2 in (147, 148);
UPDATE bets SET player2 = 46  where player2 in (177);
UPDATE bets SET player2 = 130  where player2 in (134);
UPDATE bets SET player2 = 25  where player2 in (203);
*/
