<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NovaSeedChatController extends Controller
{
    /**
     * Proxy autenticado del chat embebido. El token IA permanece en el
     * servidor ERP y nunca se entrega al navegador.
     */
    public function chat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['ok' => false, 'message' => 'Sesión ERP requerida.'], 401);
        }

        $message = trim((string) $request->input('message', ''));
        if ($message === '') {
            return response()->json(['ok' => false, 'message' => 'Escribe una consulta para el Genetista Estadístico.'], 422);
        }

        $token = (string) env('NOVASEED_IA_TOKEN', '');
        $url = (string) config('services.novaseed_ia.chat_url');
        if ($token === '' || $url === '') {
            return response()->json(['ok' => false, 'message' => 'La integración del Genetista no está configurada.'], 503);
        }

        $user = Auth::user();
        $payload = [
            'content' => substr($message, 0, 12000),
            'message_kind' => 'text',
            'task_id' => $request->input('task_id') ?: null,
            'conversation_id' => $request->input('conversation_id') ?: null,
            'user_name' => substr((string) ($user->name ?: $user->email ?: 'Usuario NovaSeed'), 0, 120),
            'source' => [
                'module' => substr((string) $request->input('module', 'erp'), 0, 80),
                'route' => substr((string) $request->input('route', $request->path()), 0, 180),
            ],
        ];

        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-NovaSeed-IA-Token: ' . $token,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => (int) config('services.novaseed_ia.timeout', 90),
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $body = curl_exec($handle);
        $curlError = curl_error($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        if ($body === false || $status < 200 || $status >= 300) {
            \Log::warning('NovaSeed IA chat integration failed', [
                'status' => $status,
                'curl_error' => $curlError ?: null,
            ]);
            return response()->json([
                'ok' => false,
                'message' => $status === 401 || $status === 403
                    ? 'La integración del Genetista no está autorizada.'
                    : 'No se pudo contactar al Genetista Estadístico. Intenta nuevamente.',
            ], $status >= 400 && $status < 600 ? $status : 502);
        }

        $response = json_decode($body, true);
        if (!is_array($response) || empty($response['ok'])) {
            \Log::warning('NovaSeed IA chat integration returned invalid payload');
            return response()->json(['ok' => false, 'message' => 'El Genetista devolvió una respuesta inválida.'], 502);
        }

        return response()->json($response);
    }
}
