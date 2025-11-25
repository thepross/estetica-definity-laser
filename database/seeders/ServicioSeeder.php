<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;
use App\Models\Promocion;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Servicio::truncate();

        // Obtenemos algunas promociones existentes (por ejemplo, la primera)
        $promocion = Promocion::first(); // Asegúrate de que exista alguna promoción

        // Lista de servicios de ejemplo
        $servicios = [
            [
                'nombre' => 'Corte de cabello',
                'descripcion' => 'Corte de cabello profesional',
                'duracion' => 45,  // en minutos
                'id_promocion' => $promocion ? $promocion->id : null,
                'precio' => 25.0,
                'state' => 'a',
            ],
            [
                'nombre' => 'Manicura',
                'descripcion' => 'Manicura básica',
                'duracion' => 30,
                'id_promocion' => $promocion ? $promocion->id : null,
                'precio' => 15.0,
                'state' => 'a',
            ],
            [
                'nombre' => 'Masaje relajante',
                'descripcion' => 'Masaje de 1 hora',
                'duracion' => 60,
                'id_promocion' => $promocion ? $promocion->id : null,
                'precio' => 40.0,
                'state' => 'a',
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
