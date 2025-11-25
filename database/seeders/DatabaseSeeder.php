<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            EmpresaSeeder::class,
            ContadorSeeder::class,
            RoleSeeder::class,
            PromocionSeeder::class,
            CategoriaSeeder::class,
            PlanPagoSeeder::class,
            UserSeeder::class,
            ClienteSeeder::class,
            VentaSeeder::class,
            PrivilegioSeeder::class,
            ServicioSeeder::class,
            ProductoSeeder::class,
            InventarioSeeder::class,
            PagoSeeder::class,
            CitaSeeder::class,
            DetalleVentaSeeder::class,
        ]);
    }
}
