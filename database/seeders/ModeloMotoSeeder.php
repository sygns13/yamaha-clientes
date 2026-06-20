<?php

namespace Database\Seeders;

use App\Models\ModeloMoto;
use Illuminate\Database\Seeder;

class ModeloMotoSeeder extends Seeder
{
    public function run(): void
    {
        // Desactiva todos los modelos existentes antes de sincronizar
        ModeloMoto::query()->update(['activo' => false]);

        $modelos = [
            ['nombre' => 'T115 Crypton',        'descripcion' => 'Scooter utilitario 115cc de bajo consumo'],
            ['nombre' => 'Cygnus RayZR',         'descripcion' => 'Scooter deportivo 125cc con tecnología Blue Core'],
            ['nombre' => 'NMAX CONNECTED',        'descripcion' => 'Scooter premium 155cc con conectividad inteligente'],
            ['nombre' => 'YB125 CHACARERA',       'descripcion' => 'Moto de trabajo y campo 125cc'],
            ['nombre' => 'YBR-Z',                 'descripcion' => 'Commuter 125cc económico y versátil'],
            ['nombre' => 'FZS 3.0',               'descripcion' => 'Naked sport 150cc con inyección de combustible'],
            ['nombre' => 'FZS 4.0 ABS',           'descripcion' => 'Naked sport 150cc con ABS y FI'],
            ['nombre' => 'FZ-X CONNECTED',        'descripcion' => 'Neo-retro naked 150cc con conectividad inteligente'],
            ['nombre' => 'FZ25 ABS CONNECTED',    'descripcion' => 'Naked sport 250cc ABS con conectividad'],
            ['nombre' => 'YZF-R15 ABS',           'descripcion' => 'Supersport 155cc con ABS y tecnología MotoGP'],
            ['nombre' => 'YZF-R3 ABS',            'descripcion' => 'Supersport 321cc bicilíndrica con ABS'],
            ['nombre' => 'YZF-R3 ABS 70th.',      'descripcion' => 'Supersport 321cc edición 70 aniversario Yamaha'],
            ['nombre' => 'MT-15',                  'descripcion' => 'Naked sport 155cc de alto rendimiento'],
            ['nombre' => 'MT-03',                  'descripcion' => 'Naked sport 321cc bicilíndrica'],
            ['nombre' => 'XTZ125',                 'descripcion' => 'Trail / enduro 125cc para todo terreno'],
            ['nombre' => 'XTZ150',                 'descripcion' => 'Trail / enduro 150cc para todo terreno'],
            ['nombre' => 'WR155R',                 'descripcion' => 'Enduro off-road 155cc de competición'],
            ['nombre' => 'XTZ250 ABS',             'descripcion' => 'Trail adventure 250cc con ABS'],
            ['nombre' => 'MT-07 ABS',              'descripcion' => 'Naked sport 689cc bicilíndrica CP2 con ABS'],
            ['nombre' => 'TRACER 09 GT',           'descripcion' => 'Sport touring 889cc tricilíndrica CP3'],
            ['nombre' => 'MT-09 ABS',              'descripcion' => 'Naked sport 889cc tricilíndrica CP3 con ABS'],
            ['nombre' => 'MT-09 SP',               'descripcion' => 'Naked sport 889cc CP3 edición especial'],
            ['nombre' => 'YZF-R7',                 'descripcion' => 'Supersport 689cc bicilíndrica CP2'],
            ['nombre' => 'YZF-R1',                 'descripcion' => 'Supersport 998cc de competición derivada MotoGP'],
            ['nombre' => 'TENERE 700',             'descripcion' => 'Adventure touring 689cc bicilíndrica CP2'],
        ];

        foreach ($modelos as $modelo) {
            ModeloMoto::updateOrCreate(
                ['nombre' => $modelo['nombre']],
                ['descripcion' => $modelo['descripcion'], 'activo' => true]
            );
        }
    }
}
