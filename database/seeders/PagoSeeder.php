<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\PlanPago;
use App\Models\Venta;
use Carbon\Carbon;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos la tabla
        Pago::truncate();

        // Obtenemos planes de pago existentes
        $planes = PlanPago::all();

        foreach ($planes as $plan) {
            // Creamos pagos parciales según la cantidad de cuotas
            for ($i = 1; $i <= $plan->cantidad_cuotas; $i++) {
                Pago::create([
                    'id_plan' => $plan->id,
                    'id_venta' => null,
                    'estado_pago' => 'pendiente',
                    'fecha_pago' => Carbon::parse($plan->fecha_inicio)->addMonths($i - 1)->format('Y-m-d'),
                    'monto' => $plan->monto_cuota,
                    'tipo_pago' => 'efectivo', // puedes cambiar a 'tarjeta', 'transferencia', etc.
                    'state' => 'a',
                ]);
            }
        }

        // Obtenemos ventas individuales
        $ventas = Venta::all();

        foreach ($ventas as $venta) {
            // Creamos un pago vinculado a la venta
            Pago::create([
                'id_plan' => null,
                'id_venta' => $venta->id,
                'estado_pago' => 'pendiente',
                'fecha_pago' => Carbon::parse($venta->fecha_venta)->format('Y-m-d'),
                'monto' => $venta->total,
                'tipo_pago' => 'efectivo', // puedes cambiar según sea necesario
                'state' => 'a',
            ]);
        }
    }
}
