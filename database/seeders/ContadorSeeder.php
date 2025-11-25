<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contador;

class ContadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //tipo = 1
        $contar = new Contador();
        $contar->nombre = 'Categoria';
        $contar->visitas = 0;
        $contar->tipo = 1;
        $contar->save();
        
        //tipo = 2
        $contar = new Contador();
        $contar->nombre = 'Cliente';
        $contar->visitas = 0;
        $contar->tipo = 2;
        $contar->save();

        //tipo = 3
        $contar = new Contador();
        $contar->nombre = 'Empleado';
        $contar->visitas = 0;
        $contar->tipo = 3;
        $contar->save();

        //tipo = 4
        $contar = new Contador();
        $contar->nombre = 'Menu';
        $contar->visitas = 0;
        $contar->tipo = 4;
        $contar->save();
        
        //tipo = 5
        $contar = new Contador();
        $contar->nombre = 'Pago';
        $contar->visitas = 0;
        $contar->tipo = 5;
        $contar->save();

        //tipo = 6
        $contar = new Contador();
        $contar->nombre = 'Privilegio';
        $contar->visitas = 0;
        $contar->tipo = 6;
        $contar->save();

        //tipo = 7
        $contar = new Contador();
        $contar->nombre = 'Producto';
        $contar->visitas = 0;
        $contar->tipo = 7;
        $contar->save();
        
        //tipo = 8
        $contar = new Contador();
        $contar->nombre = 'Promocion';
        $contar->visitas = 0;
        $contar->tipo = 8;
        $contar->save();

        //tipo = 9
        $contar = new Contador();
        $contar->nombre = 'Rol';
        $contar->visitas = 0;
        $contar->tipo = 9;
        $contar->save();

        //tipo = 10
        $contar = new Contador();
        $contar->nombre = 'Servicio';
        $contar->visitas = 0;
        $contar->tipo = 10;
        $contar->save();

        //tipo = 11
        $contar = new Contador();
        $contar->nombre = 'Usuario';
        $contar->visitas = 0;
        $contar->tipo = 11;
        $contar->save();

        //tipo = 12
        $contar = new Contador();
        $contar->nombre = 'Venta';
        $contar->visitas = 0;
        $contar->tipo = 12;
        $contar->save();
    }
}
