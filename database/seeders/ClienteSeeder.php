<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Role;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el rol "Cliente"
        $rolCliente = Role::where('nombre', 'Cliente')->first();

        // Crear un usuario para el cliente
        $userCliente = Usuario::create([
            'nombre' => 'Cliente Ejemplo',
            'email' => 'cliente1@empresa.com',
            'password' => bcrypt('cliente123'), // siempre bcrypt
            'telefono' => '99887766',
            'id_empresa' => 1, // si siempre hay una empresa por defecto
            'id_rol' => $rolCliente->id,
        ]);

        // Crear el cliente asociado a ese usuario
        Cliente::create([
            'nombre' => 'Cliente Ejemplo',
            'telefono' => '99887766',
            'direccion' => 'Calle Falsa 123',
            'id_user' => $userCliente->id,
        ]);

        // Puedes crear varios clientes de la misma forma
        $userCliente2 = Usuario::create([
            'nombre' => 'Cliente Dos',
            'email' => 'cliente2@empresa.com',
            'password' => bcrypt('cliente456'),
            'telefono' => '88776655',
            'id_empresa' => 1,
            'id_rol' => $rolCliente->id,
        ]);

        Cliente::create([
            'nombre' => 'Cliente Dos',
            'telefono' => '88776655',
            'direccion' => 'Avenida Siempre Viva 456',
            'id_user' => $userCliente2->id,
        ]);
    }
}
