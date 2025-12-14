<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetallePago;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagoFacilController extends Controller
{
    private $tokenService = "51247fae280c20410824977b0781453df59fad5b23bf2a0d14e884482f91e09078dbe5966e0b970ba696ec4caf9aa5661802935f86717c481f1670e63f35d504a62547a9de71bfc76be2c2ae01039ebcb0f74a96f0f1f56542c8b51ef7a2a6da9ea16f23e52ecc4485b69640297a5ec6a701498d2f0e1b4e7f4b7803bf5c2eba";
    private $tokenSecret = "0C351C6679844041AA31AF9C";
    private $baseUrl = "https://masterqr.pagofacil.com.bo/api/services/v2";

    /**
     * Obtener el Bearer Token (se cachea por 3 horas)
     */
    private function getBearerToken()
    {
        return Cache::remember('pagofacil_bearer_token', 3600, function () { // 1 hora de cache

            $response = Http::withHeaders([
                'tcTokenService' => $this->tokenService,
                'tcTokenSecret' => $this->tokenSecret,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/login');

            Log::info("Login PagoFácil", [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error("Error al autenticar con PagoFácil", [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception("No se pudo autenticar con PagoFácil: " . $response->body());
            }

            $data = $response->json();

            // El token está en values.accessToken según tu respuesta
            $token = $data['values']['accessToken'] ?? null;

            if (!$token) {
                Log::error("Token no encontrado en respuesta", ['data' => $data]);
                throw new \Exception("Token no encontrado en la respuesta de login");
            }

            Log::info("Token obtenido exitosamente", ['token_preview' => substr($token, 0, 20) . '...']);
            return $token;
        });
    }

    public function generarQR(Request $request)
    {
        try {
            $pago = Pago::findOrFail($request->pago_id);
            $pedido = $pago->pedido;
            $usuario = $pedido->user;
            $monto = $request->monto;
            $monto_pendiente = $pedido->getSaldoPendienteAttribute();

            if ($monto >= $monto_pendiente && $monto < 0) {
                return response()->json(["error" => "El pedido ya está pagado."]);
            }

            // Preparar detalle de orden
            $orderDetail = [];
            foreach ($pedido->detalles as $index => $detalle) {
                $orderDetail[] = [
                    "serial" => $index + 1,
                    "product" => $detalle->producto->nombre,
                    "quantity" => $detalle->cantidad,
                    "price" => (float) $detalle->subtotal,
                    "discount" => 0,
                    "total" => (float) ($detalle->subtotal * $detalle->cantidad)
                ];
            }

            // Obtener token
            $bearerToken = $this->getBearerToken();

            // Generar QR
            Log::info("CALLBACK URL", ['url' => route('pagofacil.callback')]);
            $resp = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $bearerToken
                ])
                ->post($this->baseUrl . '/generate-qr', [
                    "paymentMethod" => 4,
                    "clientName" => $usuario->name ?? "Cliente",
                    "documentType" => 1,
                    "documentId" => $usuario->ci ?? "0",
                    "phoneNumber" => $usuario->telefono ?? "00000000",
                    "email" => $usuario->email,
                    "paymentNumber" => "PED-" . $pedido->id . "-" . time(),
                    "amount" => (float) $monto,
                    "currency" => 2,
                    "clientCode" => "11001",
                    // "callbackUrl" => "https://www.tecnoweb.org.bo/inf513/grupo06sc/proyecto2/public/api/pagofacil/callback",
                    "callbackUrl" => route('pagofacil.callback'),
                    "orderDetail" => $orderDetail
                ]);

            if (!$resp->successful()) {
                Log::error("Error PagoFácil generate-qr", [
                    'status' => $resp->status(),
                    'body' => $resp->body()
                ]);
                return response()->json([
                    "error" => "Error al generar QR",
                    "status" => $resp->status(),
                    "details" => $resp->json()
                ]);
            }

            $data = $resp->json();
            Log::info("Respuesta generate-qr", $data);

            // Extraer datos según la respuesta real que vimos
            $transaccionId = $data['values']['transactionId'] ?? null;
            $qrBase64 = $data['values']['qrBase64'] ?? null;

            if (!$transaccionId || !$qrBase64) {
                return response()->json([
                    "error" => "Respuesta incompleta de PagoFácil",
                    "data" => $data
                ]);
            }

            // Guardar transacción en BD
            // $pago->transaccion_qr = $transaccionId;
            $pago->save();

            return response()->json([
                "qr" => "data:image/png;base64," . $qrBase64,
                "transaccion" => $transaccionId,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error("Excepción en generarQR", [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                "error" => "Error interno: " . $e->getMessage()
            ], 500);
        }
    }

    public function consultarEstado(Request $request)
    {
        try {
            $bearerToken = $this->getBearerToken();

            Log::info("Consultando estado - Request", [
                'transactionId' => $request->tnTransaccion,
                'url' => $this->baseUrl . '/query-transaction'
            ]);

            // Es POST y usa pagofacilTransactionId en el body
            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post($this->baseUrl . '/query-transaction', [
                'pagofacilTransactionId' => $request->tnTransaccion
            ]);

            Log::info("Respuesta consulta estado", [
                'status' => $resp->status(),
                'body' => $resp->json()
            ]);

            if (!$resp->successful()) {
                return response()->json([
                    "error" => "Error al consultar estado",
                    "status" => $resp->status(),
                    "details" => $resp->json()
                ]);
            }

            $data = $resp->json();

            // El estado viene en values.paymentStatus
            $paymentStatus = $data['values']['paymentStatus'] ?? null;

            // Mapear el ID del estado a texto legible
            $estadoTexto = "DESCONOCIDO";
            switch ($paymentStatus) {
                case 1:
                    $estadoTexto = "PENDIENTE";
                    break;
                case 2:
                    $estadoTexto = "COMPLETADO";
                    break;
                case 3:
                    $estadoTexto = "RECHAZADO";
                    break;
                case 4:
                    $estadoTexto = "ANULADO";
                    break;
            }

            return response()->json([
                "estado" => $estadoTexto,
                "paymentStatus" => $paymentStatus,
                "data" => $data
            ]);
        } catch (\Exception $e) {
            Log::error("Excepción consultarEstado", [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                "error" => "Error: " . $e->getMessage()
            ], 500);
        }
    }

    public function urlCallback(Request $request)
    {
        try {
            Log::info("Callback PagoFácil recibido", $request->all());

            // Leer campos reales que envía PagoFácil
            $pedidoID   = $request->input('PedidoID'); // "PED-21-1765746251"
            $estado     = $request->input('Estado');
            $fecha      = $request->input('Fecha');
            $hora       = $request->input('Hora');
            $metodoPago = $request->input('MetodoPago');

            if (!$pedidoID) {
                Log::error("No se recibió PedidoID en el callback");
                return response()->json([
                    'error' => 1,
                    'status' => 0,
                    'message' => 'PedidoID requerido',
                    'messageMostrar' => 0,
                    'messageSistema' => 'Campo PedidoID ausente',
                    'values' => false
                ], 400);
            }

            // AQUÍ ESTÁ EL CAMBIO PRINCIPAL
            // Extraer el ID del pedido de "PED-21-1765746251" -> "21"
            preg_match('/PED-(\d+)-/', $pedidoID, $matches);
            $pedidoId = $matches[1] ?? null;

            if (!$pedidoId) {
                Log::error("No se pudo extraer el ID del pedido", ['PedidoID' => $pedidoID]);
                return response()->json([
                    'error' => 1,
                    'status' => 0,
                    'message' => 'Formato de PedidoID inválido',
                    'messageMostrar' => 0,
                    'messageSistema' => 'No se pudo extraer ID',
                    'values' => false
                ], 400);
            }

            Log::info("ID del pedido extraído", ['pedido_id' => $pedidoId, 'pedidoID_completo' => $pedidoID]);

            // Buscar el pago más reciente para este pedido que no esté completado
            $pago = Pago::where('pedido_id', $pedidoId)
                ->whereDoesntHave('detallePagos') // O la condición que uses para pagos pendientes
                ->latest()
                ->first();

            // Si no hay pago sin detalles, buscar el último pago del pedido
            if (!$pago) {
                $pago = Pago::where('pedido_id', $pedidoId)
                    ->latest()
                    ->first();
            }

            if (!$pago) {
                Log::warning("Pago no encontrado para pedido", ['pedido_id' => $pedidoId, 'pedidoID' => $pedidoID]);
                return response()->json([
                    'error' => 1,
                    'status' => 0,
                    'message' => 'Pago no encontrado',
                    'messageMostrar' => 0,
                    'messageSistema' => "No existe pago para pedido {$pedidoId}",
                    'values' => false
                ], 404);
            }

            // Guardar el ID de transacción de PagoFácil
            if (!$pago->transaccion_qr) {
                $pago->transaccion_qr = $pedidoID;
                $pago->save();
                Log::info("Transacción QR guardada", ['pago_id' => $pago->id, 'transaccion' => $pedidoID]);
            }

            // Estados que indican que el pago fue completado
            $estadosPagado = ['2', 2, 'COMPLETED', 'PAGADO', 'APPROVED', 'SUCCESS'];

            if (in_array($estado, $estadosPagado)) {
                $pedido = $pago->pedido;
                $monto  = $pedido->saldoPendiente();

                // Verificar que no se duplique el pago
                $yaRegistrado = DetallePago::where('pago_id', $pago->id)
                    ->where('monto', $monto)
                    ->whereDate('created_at', today())
                    ->exists();

                if ($yaRegistrado) {
                    Log::info("Pago ya registrado anteriormente", ['pago_id' => $pago->id]);
                    return response()->json([
                        'error' => 0,
                        'status' => 1,
                        'message' => 'Pago ya fue registrado',
                        'messageMostrar' => 0,
                        'messageSistema' => '',
                        'values' => true
                    ], 200);
                }

                // Registrar el pago
                DetallePago::create([
                    'pago_id' => $pago->id,
                    'fecha'   => $fecha ?? now()->format('Y-m-d'),
                    'hora'    => $hora ?? now()->format('H:i:s'),
                    'monto'   => $monto,
                    'saldo'   => 0
                ]);

                $pago->monto = $pago->detallePagos()->sum('monto');
                $pago->save();

                Log::info("Pago procesado exitosamente", [
                    'pago_id' => $pago->id,
                    'pedido_id' => $pedidoId,
                    'monto'   => $monto,
                    'metodo'  => $metodoPago
                ]);
            }

            return response()->json([
                'error' => 0,
                'status' => 1,
                'message' => 'Pago realizado correctamente',
                'messageMostrar' => 0,
                'messageSistema' => '',
                'values' => true
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error en callback: " . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 1,
                'status' => 0,
                'message' => 'Ocurrió un error al procesar la transacción',
                'messageMostrar' => 0,
                'messageSistema' => $e->getMessage(),
                'values' => false
            ], 500);
        }
    }
}
