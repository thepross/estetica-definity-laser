<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Tratamientos faciales'],
            ['nombre' => 'Tratamientos corporales'],
            ['nombre' => 'Depilación'],
            ['nombre' => 'Masajes relajantes'],
            ['nombre' => 'Rejuvenecimiento'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
