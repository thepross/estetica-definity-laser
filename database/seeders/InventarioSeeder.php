<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventario;
use App\Models\Producto;

class InventarioSeeder extends Seeder
{
    public function run()
    {
        // Limpiamos la tabla
        Inventario::truncate();

        // Traemos todos los productos existentes
        $productos = Producto::all();

        // Si no hay productos, no hacemos nada
        if ($productos->isEmpty()) {
            $this->command->info('No hay productos para agregar al inventario.');
            return;
        }

        $inventarioItems = [];

        foreach ($productos as $producto) {
            $inventarioItems[] = [
                'cantidad' => rand(10, 50), // Cantidad aleatoria
                'descripcion' => 'Inventario inicial de ' . $producto->nombre,
                'fecha' => now(),
                'id_producto' => $producto->id,
                'state' => 'a',
                'tipo' => 'producto terminado', // o 'insumo' según el producto
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertamos todos los registros de una vez
        Inventario::insert($inventarioItems);
    }
}
