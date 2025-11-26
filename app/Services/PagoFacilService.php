<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PagoFacilService
{
    public function generarQr($venta)
    {
        $token = $this->getAccessToken();
        $cliente = $venta->cliente;

        $data = [
            'paymentMethod' => 4,
            'clientName' => $cliente->nombre,
            'documentType' => 1,
            'documentId' => $cliente->id_user . "",
            'phoneNumber' => $cliente->telefono . "",
            'email' => $cliente->user->email ?? 'sin_correo@admin.com',
            'paymentNumber' => "V" . $venta->id,
            'amount' => floatval($venta->total),
            'currency' => 2,
            'clientCode' => "CLIGRUPO005SC-" . $cliente->id,
            'callbackUrl' => route('pagofacil.callback'),
            // 'callbackUrl' => "https://tecnoweb.org.bo/inf513/grupo05sc/proyecto2/public/pagofacil/callback",
            // 'callbackUrl' => config('services.pagofacil.callback_url'),
            'orderDetail' => [
                [
                    'serial' => $venta->id,
                    'product' => $venta->id . "|" . $venta->fecha_venta,
                    'quantity' => 1,
                    'price' => floatval($venta->total),
                    'discount' => 0,
                    'total' => floatval($venta->total)
                ]
            ]
        ];

        // dd($cliente, $data, $venta);
        $response = Http::withToken($token)->post(config('services.pagofacil.base_url') . '/generate-qr', $data);
        if ($response->successful() && (int) $response->json('error') === 0) {
            return $response->json('values');
        }
        throw new \Exception('Error al generar el QR de Pago Facil: ' . $response->body());
    }
    public function generarQrParaCuota($cuota)
    {
        $token = $this->getAccessToken();
        $cliente = $cuota->venta->cliente;

        $data = [
            'paymentMethod' => 4,
            'clientName' => $cliente->nombre,
            'documentType' => 1,
            'documentId' => $cliente->id_user . "",
            'phoneNumber' => $cliente->telefono . "",
            'email' => $cliente->user->email ?? 'sin_correo@admin.com',
            'paymentNumber' => "V" . $cuota->venta->id . "-C" . $cuota->id,
            'amount' => floatval($cuota->monto),
            'currency' => 2,
            'clientCode' => "CLIGRUPO005SC-" . $cliente->id,
            // 'callbackUrl' => route('pagofacil.callback'),
            'callbackUrl' => "https://tecnoweb.org.bo/inf513/grupo05sc/proyecto2/public/pagofacil/callback",
            // 'callbackUrl' => config('services.pagofacil.callback_url'),
            'orderDetail' => [
                [
                    'serial' => $cuota->id,
                    'product' => $cuota->venta->concepto . " (Cuota #" . $cuota->id . ")",
                    'quantity' => 1,
                    'price' => floatval($cuota->monto),
                    'discount' => 0,
                    'total' => floatval($cuota->monto)
                ]
            ]
        ];

        // dd($cliente, $data, $cuota);

        $response = Http::withToken($token)->post(config('services.pagofacil.base_url') . '/generate-qr', $data);
        // dd($response);
        if ($response->successful() && (int) $response->json('error') === 0) {
            return $response->json('values');
        }
        throw new \Exception('Error al generar el QR de Pago Facil: ' . $response->body());
    }

    protected function getAccessToken()
    {
        return Cache::remember('pagofacil_token', 600, function () {
            $response = Http::withHeaders([
                'tcTokenService' => config('services.pagofacil.service_token'),
                'tcTokenSecret' => config('services.pagofacil.secret_token'),
            ])->post(config('services.pagofacil.base_url') . '/login');
            return $response->json('values.accessToken');
        });
    }
}
