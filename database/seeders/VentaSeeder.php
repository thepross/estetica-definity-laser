<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;

class VentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Suponiendo que ya existen clientes y usuarios
        $cliente = Cliente::first(); // Cliente existente
        $usuario = User::where('state', 'a')->first(); // Usuario activo que registra la venta

        Venta::create([
            'estado' => 'pendiente', // o 'finalizada', según tu lógica
            'fecha_venta' => Carbon::now()->format('Y-m-d'),
            'id_cliente' => $cliente ? $cliente->id : null,
            'id_usuario' => $usuario->id,
            'total' => 50.00,
            'state' => 'a',
        ]);
    }
}
