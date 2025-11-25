<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagoFacilWebHookController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Webhook PagoFácil recibido:', $request->all());

        $data = $request->all();
        $pedidoID = $data['PedidoID'];

        // -------------------------------
        //  Caso 1: Pago CON cuota (V1-C1)
        // -------------------------------
        if (preg_match('/V(\d+)-C(\d+)/', $pedidoID, $m)) {
            $ventaId = $m[1];
            $cuotaId = $m[2];

            $cuota = Cuota::find($cuotaId);

            if ($cuota) {
                $cuota->update([
                    'estado' => 'pagado',
                    'updated_at' => now(),
                ]);

                // Verificar si todas las cuotas ya están pagadas
                $cuotasPendientes = $cuota->venta->cuotas()->where('estado', '!=', 'pagado')->count();

                $cuota->venta->update([
                    'estado' => $cuotasPendientes === 0 ? 'pagado' : 'parcial'
                ]);
            }

            return response()->json([
                'error' => 0,
                'status' => 1,
                'message' => "Pago registrado para cuota {$cuotaId} de la venta {$ventaId}.",
            ]);
        }

        // -------------------------------
        //  Caso 2: Pago SIN cuota (solo V2 por ejemplo)
        // -------------------------------
        if (preg_match('/V(\d+)/', $pedidoID, $m)) {
            $ventaId = $m[1];

            $venta = \App\Models\Venta::find($ventaId);

            if ($venta) {
                $venta->update([
                    'estado' => 'pagado',
                    'updated_at' => now(),
                ]);
            }

            return response()->json([
                'error' => 0,
                'status' => 1,
                'message' => "Pago registrado para venta {$ventaId} (sin cuotas).",
            ]);
        }

        // -------------------------------
        // Si llega un formato inesperado
        // -------------------------------
        return response()->json([
            'error' => 1,
            'status' => 0,
            'message' => "Formato de PedidoID no reconocido: {$pedidoID}",
        ]);
    }

}
