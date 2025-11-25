<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Servicio;
use Faker\Factory as Faker;
use Carbon\Carbon;

class CitaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Limpiamos la tabla
        Cita::truncate();

        // Obtenemos clientes, médicos y secretarias
        $clientes = Cliente::all();
        $medicos = Usuario::whereHas('rol', function($q) {
            $q->where('nombre', 'Medico');
        })->get();

        $secretarias = Usuario::whereHas('rol', function($q) {
            $q->where('nombre', 'Secretaria');
        })->get();

        $servicios = Servicio::all();

        // Generamos 20 citas de ejemplo
        for ($i = 0; $i < 20; $i++) {
            $cliente = $clientes->random();
            $medico = $medicos->random();
            $secretaria = $secretarias->random();
            $servicio = $servicios->random();

            $fechaHora = $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d H:i:s');

            Cita::create([
                'estado' => $faker->randomElement(['pendiente', 'confirmada', 'cancelada']),
                'fecha_hora' => $fechaHora,
                'id_cliente' => $cliente->id,
                'id_medico' => $medico->id,
                'id_secretaria' => $secretaria->id,
                'id_servicio' => $servicio->id,
                'state' => 'a',
            ]);
        }
    }
}
