<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetalleVenta;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Servicio;
use Faker\Factory as Faker;

class DetalleVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Limpiamos la tabla
        DetalleVenta::truncate();

        $ventas = Venta::all();
        $productos = Producto::all();
        $servicios = Servicio::all();

        foreach ($ventas as $venta) {
            // Número aleatorio de productos y servicios por venta
            $numProductos = rand(1, 3);
            $numServicios = rand(1, 2);

            // Productos
            $productos->random($numProductos)->each(function ($producto) use ($venta, $faker) {
                $cantidad = rand(1, 5);
                DetalleVenta::create([
                    'cantidad' => $cantidad,
                    'id_venta' => $venta->id,
                    'id_producto' => $producto->id,
                    'id_servicio' => null, // solo producto
                    'precio' => $producto->precio,
                    'state' => 'a',
                ]);
            });

            // Servicios
            $servicios->random($numServicios)->each(function ($servicio) use ($venta, $faker) {
                $cantidad = 1; // normalmente 1 por servicio
                DetalleVenta::create([
                    'cantidad' => $cantidad,
                    'id_venta' => $venta->id,
                    'id_producto' => null, // solo servicio
                    'id_servicio' => $servicio->id,
                    'precio' => $servicio->precio,
                    'state' => 'a',
                ]);
            });
        }
    }
}
