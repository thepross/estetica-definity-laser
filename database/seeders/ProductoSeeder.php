<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Promocion;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos la tabla (opcional)
        Producto::truncate();

        // Asegurarnos de que existan categorías y promociones
        $categorias = Categoria::all();
        $promocion = Promocion::first(); // Asumimos que hay al menos una promoción

        $productos = [
            [
                'nombre' => 'Crema facial hidratante',
                'descripcion' => 'Crema especialmente formulada para pieles sensibles',
                'fecha_ingreso' => now(),
                'id_promocion' => $promocion ? $promocion->id : null,
                'precio' => 25.50,
                'id_categoria' => $categorias->where('nombre', 'Tratamientos faciales')->first()->id ?? null,
                'state' => 'a',
                'stock' => 50,
            ],
            [
                'nombre' => 'Aceite de masaje relajante',
                'descripcion' => 'Aceite aromático ideal para masajes relajantes',
                'fecha_ingreso' => now(),
                'id_promocion' => $promocion ? $promocion->id : null,
                'precio' => 18.00,
                'id_categoria' => $categorias->where('nombre', 'Masajes relajantes')->first()->id ?? null,
                'state' => 'a',
                'stock' => 30,
            ],
            [
                'nombre' => 'Exfoliante corporal',
                'descripcion' => 'Exfoliante para tratamientos corporales y rejuvenecimiento',
                'fecha_ingreso' => now(),
                'id_promocion' => $promocion ? $promocion->id : null,
                'precio' => 22.00,
                'id_categoria' => $categorias->where('nombre', 'Tratamientos corporales')->first()->id ?? null,
                'state' => 'a',
                'stock' => 40,
            ],
            [
                'nombre' => 'Kit depilación en casa',
                'descripcion' => 'Kit completo para depilación segura y efectiva',
                'fecha_ingreso' => now(),
                'id_promocion' => $promocion ? $promocion->id : null,
                'precio' => 30.00,
                'id_categoria' => $categorias->where('nombre', 'Depilación')->first()->id ?? null,
                'state' => 'a',
                'stock' => 25,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
