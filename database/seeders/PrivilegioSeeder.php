<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Privilegio;

class PrivilegioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos la tabla
        Privilegio::truncate();

        // Obtener IDs de roles
        $adminRole = Role::where('nombre', 'Administrador')->first();
        $secretariaRole = Role::where('nombre', 'Secretaria')->first();
        $medicoRole = Role::where('nombre', 'Medico')->first();

        // Si no existen los roles, salir o crearlos (asumimos que RoleSeeder ya corrió)
        if (!$adminRole || !$secretariaRole || !$medicoRole) {
            $this->command->error('Roles no encontrados. Ejecuta RoleSeeder primero.');
            return;
        }

        // Definición de permisos por defecto (todo falso)
        $defaultPerms = ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false];

        // 1. Configuración ADMINISTRADOR (Todo True)
        $funcionalidadesAdmin = [
            'Categoria', 'Cita', 'Cliente', 'Inventario', 'Pago', 'PlanPago',
            'Privilegio', 'Producto', 'Promocion', 'Reportes', 'Rol',
            'Servicio', 'Usuario', 'Venta', 'Administracion', 'Empresa'
        ];

        foreach ($funcionalidadesAdmin as $func) {
            Privilegio::create([
                'funcionalidad' => $func,
                'id_rol' => $adminRole->id,
                'agregar' => true, 'borrar' => true, 'modificar' => true, 'leer' => true,
                'state' => 'a'
            ]);
        }

        // 2. Configuración SECRETARIA
        $permisosSecretaria = [
            'Cliente'       => ['leer' => true, 'agregar' => true, 'modificar' => true, 'borrar' => false],
            'Cita'          => ['leer' => true, 'agregar' => true, 'modificar' => true, 'borrar' => true], // Puede cancelar/borrar citas
            'Venta'         => ['leer' => true, 'agregar' => true, 'modificar' => false, 'borrar' => false],
            'Pago'          => ['leer' => true, 'agregar' => true, 'modificar' => false, 'borrar' => false],
            'PlanPago'      => ['leer' => true, 'agregar' => true, 'modificar' => false, 'borrar' => false],
            'Inventario'    => ['leer' => true, 'agregar' => false, 'modificar' => true, 'borrar' => false], // Actualizar stock
            'Producto'      => ['leer' => true, 'agregar' => false, 'modificar' => true, 'borrar' => false], // Ver y actualizar stock
            'Servicio'      => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Promocion'     => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Empresa'       => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Categoria'     => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Reportes'      => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Usuario'       => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Rol'           => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Privilegio'    => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Administracion'=> ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
        ];

        $this->crearPrivilegios($secretariaRole->id, $permisosSecretaria);

        // 3. Configuración MEDICO
        $permisosMedico = [
            'Cita'          => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false], // Solo ver su agenda (filtrado en controller)
            'Cliente'       => ['leer' => true, 'agregar' => false, 'modificar' => true, 'borrar' => false], // Editar historia clínica
            'Servicio'      => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Producto'      => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Inventario'    => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Promocion'     => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Empresa'       => ['leer' => true, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            // Resto false
            'Venta'         => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Pago'          => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'PlanPago'      => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Categoria'     => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Reportes'      => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Usuario'       => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Rol'           => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Privilegio'    => ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
            'Administracion'=> ['leer' => false, 'agregar' => false, 'modificar' => false, 'borrar' => false],
        ];

        $this->crearPrivilegios($medicoRole->id, $permisosMedico);
    }

    private function crearPrivilegios($rolId, $permisos)
    {
        foreach ($permisos as $funcionalidad => $ops) {
            Privilegio::create([
                'funcionalidad' => $funcionalidad,
                'id_rol' => $rolId,
                'agregar' => $ops['agregar'] ?? false,
                'borrar' => $ops['borrar'] ?? false,
                'modificar' => $ops['modificar'] ?? false,
                'leer' => $ops['leer'] ?? false,
                'state' => 'a'
            ]);
        }
    }
}
