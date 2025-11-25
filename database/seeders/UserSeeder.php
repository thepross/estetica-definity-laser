<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Role;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolAdmin = Role::where('nombre', 'Administrador')->first();
        $empresaDefault = Empresa::first();

        // Crear un usuario administrador
        Usuario::create([
            'nombre' => 'Admin Principal',
            'email' => 'admin@empresa.com',
            'password' => bcrypt('secret'),
            'telefono' => '12345678',
            'id_empresa' => $empresaDefault->id,
            'id_rol' => $rolAdmin->id,
            'state' => 'a',
        ]);

        // Crear un usuario médico de ejemplo
        $rolMedico = Role::where('nombre', 'Medico')->first();
        Usuario::create([
            'nombre' => 'Dr. Juan Perez',
            'email' => 'medico@empresa.com',
            'password' => bcrypt('secret'),
            'telefono' => '87654321',
            'id_empresa' => $empresaDefault->id,
            'id_rol' => $rolMedico->id,
            'state' => 'a',
        ]);

        // Crear un usuario secretaria de ejemplo
        $rolSecretaria = Role::where('nombre', 'Secretaria')->first();
        Usuario::create([
            'nombre' => 'Sra. Ana Lopez',
            'email' => 'secretaria@empresa.com',
            'password' => bcrypt('secret'),
            'telefono' => '11223344',
            'id_empresa' => $empresaDefault->id,
            'id_rol' => $rolSecretaria->id,
            'state' => 'a',
        ]);
    }
}
