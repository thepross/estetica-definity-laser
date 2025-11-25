<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanPago;

class PlanPagoSeeder extends Seeder
{
    public function run(): void
    {
        PlanPago::create([
            'cantidad_cuotas' => 6,
            'monto_cuota' => 150.00,
            'total_deuda' => 900.00,
            'saldo_restante' => 900.00,
            'fecha_inicio' => '2024-11-15',
        ]);

        PlanPago::create([
            'cantidad_cuotas' => 3,
            'monto_cuota' => 200.00,
            'total_deuda' => 600.00,
            'saldo_restante' => 600.00,
            'fecha_inicio' => '2024-12-01',
        ]);
    }
}
