<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

/**
 * Seed de clientes de prueba con datos peruanos realistas.
 * Usa firstOrCreate para que sea idempotente (safe re-seed).
 */
class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        // Clientes de prueba — datos ficticios con formato peruano
        $clientes = [
            [
                'nombres_apellidos' => 'Carlos Alberto Mendoza Ríos',
                'celular'           => '987654321',
                'correo'            => 'carlos.mendoza@gmail.com',
            ],
            [
                'nombres_apellidos' => 'María Elena Torres Vásquez',
                'celular'           => '956789012',
                'correo'            => 'maria.torres@hotmail.com',
            ],
            [
                'nombres_apellidos' => 'Juan Pablo Huamán Ccallo',
                'celular'           => '943210987',
                'correo'            => 'jp.huaman@outlook.com',
            ],
            [
                'nombres_apellidos' => 'Lucía Fernanda Sánchez Paredes',
                'celular'           => '978123456',
                'correo'            => 'lucia.sanchez@gmail.com',
            ],
            [
                'nombres_apellidos' => 'Roberto Alejandro Quispe Mamani',
                'celular'           => '912345678',
                'correo'            => null,
            ],
            [
                'nombres_apellidos' => 'Ana Cristina Flores Delgado',
                'celular'           => '965432109',
                'correo'            => 'ana.flores@yahoo.com',
            ],
            [
                'nombres_apellidos' => 'Miguel Ángel Rojas Condori',
                'celular'           => '934567890',
                'correo'            => 'miguel.rojas@gmail.com',
            ],
            [
                'nombres_apellidos' => 'Paola Beatriz Castillo Navarro',
                'celular'           => '989012345',
                'correo'            => 'paola.castillo@outlook.com',
            ],
            [
                'nombres_apellidos' => 'Óscar Enrique Vargas Lima',
                'celular'           => '921098765',
                'correo'            => 'oscar.vargas@gmail.com',
            ],
            [
                'nombres_apellidos' => 'Sofía Alejandra Pinto Ramos',
                'celular'           => '967890123',
                'correo'            => null,
            ],
            [
                'nombres_apellidos' => 'Diego Armando Cruz Benítez',
                'celular'           => '945678901',
                'correo'            => 'diego.cruz@gmail.com',
            ],
            [
                'nombres_apellidos' => 'Valeria Milagros Aguirre Cárdenas',
                'celular'           => '998765432',
                'correo'            => 'valeria.aguirre@hotmail.com',
            ],
            [
                'nombres_apellidos' => 'Fernando José Salinas Herrera',
                'celular'           => '933214567',
                'correo'            => 'fernando.salinas@gmail.com',
            ],
            [
                'nombres_apellidos' => 'Carmen Rosa Espinoza Gutierrez',
                'celular'           => '976543210',
                'correo'            => 'carmen.espinoza@yahoo.com',
            ],
            [
                'nombres_apellidos' => 'Andrés Nicolás Bellido Tapia',
                'celular'           => '954321098',
                'correo'            => 'andres.bellido@outlook.com',
            ],
        ];

        foreach ($clientes as $cliente) {
            // firstOrCreate evita duplicados en re-ejecuciones del seeder
            Cliente::firstOrCreate(
                ['celular' => $cliente['celular']],
                [
                    'nombres_apellidos' => $cliente['nombres_apellidos'],
                    'correo'            => $cliente['correo'],
                ]
            );
        }
    }
}
