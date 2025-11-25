<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promocion;

class PromocionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promocion::create([
            'nombre' => 'Descuento Verano',
            'descripcion' => 'Promoción especial de verano',
            'descuento' => 10,
            'fecha_inicio' => '2024-06-24',
            'fecha_fin' => '2024-06-29',
        ]);
    }
}
