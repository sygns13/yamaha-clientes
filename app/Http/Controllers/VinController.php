<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVin1Request;
use App\Http\Requests\StoreVin2Request;
use App\Models\Cliente;
use App\Models\ModeloMoto;
use App\Models\Motocicleta;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class VinController extends Controller
{
    public function vin1(): Response
    {
        return Inertia::render('Vin/Formulario1', [
            'modelos' => ModeloMoto::where('activo', true)->orderBy('nombre')->get(['id', 'nombre']),
        ]);
    }

    public function storeVin1(StoreVin1Request $request): RedirectResponse
    {
        $cliente = Cliente::create($request->only(['nombres_apellidos', 'celular', 'correo']));

        $esOtros = $request->modelo_moto_id === 'otros';

        Motocicleta::create([
            'cliente_id'       => $cliente->id,
            'modelo_moto_id'   => $esOtros ? null : $request->modelo_moto_id,
            'modelo_moto_otro' => $esOtros ? $request->modelo_moto_otro : null,
            'tipo_formulario'  => 'VIN1',
            'vin_descripcion'  => $request->vin_descripcion,
        ]);

        return redirect()->route('vin1')
            ->with('success', '¡Registro exitoso! Tu información ha sido guardada correctamente.');
    }

    public function vin2(): Response
    {
        return Inertia::render('Vin/Formulario2', [
            'modelos' => ModeloMoto::where('activo', true)->orderBy('nombre')->get(['id', 'nombre']),
        ]);
    }

    public function storeVin2(StoreVin2Request $request): RedirectResponse
    {
        $path = $request->file('vin_imagen')->store('vin_imagenes', 'public');

        $cliente = Cliente::create($request->only(['nombres_apellidos', 'celular', 'correo']));

        $esOtros = $request->modelo_moto_id === 'otros';

        Motocicleta::create([
            'cliente_id'       => $cliente->id,
            'modelo_moto_id'   => $esOtros ? null : $request->modelo_moto_id,
            'modelo_moto_otro' => $esOtros ? $request->modelo_moto_otro : null,
            'anio_modelo'      => $request->anio_modelo,
            'tipo_formulario'  => 'VIN2',
            'vin_descripcion'  => $request->vin_descripcion,
            'vin_imagen'       => $path,
        ]);

        return redirect()->route('vin')
            ->with('success', '¡Registro exitoso! Tu información ha sido guardada correctamente.');
    }
}
