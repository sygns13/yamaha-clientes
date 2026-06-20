<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\ModeloMoto;
use App\Models\Motocicleta;
use Illuminate\Database\Seeder;

/**
 * Seed de registros de motocicletas para datos de prueba.
 * Crea una mezcla de registros VIN-1 (texto) y VIN-2 (foto sin imagen real).
 * Depende de: ClienteSeeder y ModeloMotoSeeder.
 */
class MotocicletaSeeder extends Seeder
{
    public function run(): void
    {
        // Recuperar clientes y modelos ya seedados
        $clientes = Cliente::all()->keyBy('celular');
        $modelos  = ModeloMoto::all()->keyBy('nombre');

        // Códigos VIN reales de 17 caracteres (formato Yamaha Japón / Perú)
        // Estructura: WMI (3) + VDS (6) + VIS (8)
        $registros = [
            // ── VIN-1 (descripción de texto) ───────────────────────────────
            [
                'celular'         => '987654321',
                'modelo'          => 'FZ25',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYARJ18EL5A000001',
            ],
            [
                'celular'         => '987654321',
                'modelo'          => 'MT-03',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAMT03BM5B000002',
            ],
            [
                'celular'         => '956789012',
                'modelo'          => 'FZ-S FI',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAFZS15N5C000003',
            ],
            [
                'celular'         => '943210987',
                'modelo'          => 'YZF-R3',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAYZFR3M5D000004',
            ],
            [
                'celular'         => '978123456',
                'modelo'          => 'XTZ 150',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYA3D4035P5E000005',
            ],
            [
                'celular'         => '912345678',
                'modelo'          => 'NMAX 155',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYASJ17BN5F000006',
            ],
            [
                'celular'         => '965432109',
                'modelo'          => 'Crypton',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYACRP10M5G000007',
            ],
            [
                'celular'         => '934567890',
                'modelo'          => 'FZ-X',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAFZX15P5H000008',
            ],
            [
                'celular'         => '989012345',
                'modelo'          => 'Fazer 25',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAFZ25BN5J000009',
            ],
            [
                'celular'         => '921098765',
                'modelo'          => 'YBR 125',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAYBR12L5K000010',
            ],
            [
                'celular'         => '967890123',
                'modelo'          => 'XTZ 250 Lander',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAXTZ25M5L000011',
            ],
            [
                'celular'         => '945678901',
                'modelo'          => 'Aerox 155',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAERX15P5M000012',
            ],
            [
                'celular'         => '998765432',
                'modelo'          => 'MT-07',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAMT07CM5N000013',
            ],
            [
                'celular'         => '933214567',
                'modelo'          => 'Ténéré 700',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYATN700N5P000014',
            ],
            [
                'celular'         => '976543210',
                'modelo'          => 'YZF-R1',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAYZFR1P5R000015',
            ],
            [
                'celular'         => '954321098',
                'modelo'          => 'FZ25',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYARJ18EN5A000016',
            ],
            [
                'celular'         => '943210987',
                'modelo'          => 'NMAX 155',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYASJ17BP5F000017',
            ],
            [
                'celular'         => '965432109',
                'modelo'          => 'MT-03',
                'tipo'            => 'VIN1',
                'vin_descripcion' => 'JYAMT03BP5B000018',
            ],

            // ── VIN-2 (foto del VIN — sin imagen real en el seeder) ────────
            [
                'celular'         => '912345678',
                'modelo'          => 'XTZ 150',
                'tipo'            => 'VIN2',
                'vin_descripcion' => null,
            ],
            [
                'celular'         => '978123456',
                'modelo'          => 'Aerox 155',
                'tipo'            => 'VIN2',
                'vin_descripcion' => null,
            ],
            [
                'celular'         => '956789012',
                'modelo'          => 'Crypton',
                'tipo'            => 'VIN2',
                'vin_descripcion' => null,
            ],
            [
                'celular'         => '989012345',
                'modelo'          => 'FZ-S FI',
                'tipo'            => 'VIN2',
                'vin_descripcion' => null,
            ],
            [
                'celular'         => '998765432',
                'modelo'          => 'YBR 125',
                'tipo'            => 'VIN2',
                'vin_descripcion' => null,
            ],
            [
                'celular'         => '933214567',
                'modelo'          => 'FZ-X',
                'tipo'            => 'VIN2',
                'vin_descripcion' => null,
            ],
            [
                'celular'         => '954321098',
                'modelo'          => 'Fazer 25',
                'tipo'            => 'VIN2',
                'vin_descripcion' => null,
            ],
        ];

        foreach ($registros as $r) {
            $cliente = $clientes->get($r['celular']);
            $modelo  = $modelos->get($r['modelo']);

            // Saltar si el cliente o modelo no existe (evita errores en re-seed parcial)
            if (! $cliente || ! $modelo) {
                continue;
            }

            // Evitar duplicados exactos usando la combinación cliente + modelo + tipo + VIN
            Motocicleta::firstOrCreate(
                [
                    'cliente_id'      => $cliente->id,
                    'modelo_moto_id'  => $modelo->id,
                    'tipo_formulario' => $r['tipo'],
                    'vin_descripcion' => $r['vin_descripcion'],
                ],
                [
                    'vin_imagen' => null,   // Sin imagen real en datos de prueba
                ]
            );
        }
    }
}
