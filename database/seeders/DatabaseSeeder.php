<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Importar los seeders adicionales para el registro automático

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuario administrador inicial ──────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@yamaha.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('admin12345'),
                'role'     => 'admin',
                'activo'   => true,
            ]
        );

        // ── Llamar seeders en orden de dependencia ─────────────────────────
        $this->call([
            ModeloMotoSeeder::class,   // Sin dependencias
            ClienteSeeder::class,       // Sin dependencias
            MotocicletaSeeder::class,   // Depende de Cliente y ModeloMoto
        ]);
    }
}
