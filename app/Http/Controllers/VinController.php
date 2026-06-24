<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVin1Request;
use App\Http\Requests\StoreVin2Request;
use App\Models\Cliente;
use App\Models\ModeloMoto;
use App\Models\Motocicleta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    /**
     * Busca un cliente por tipo + número de documento (consumido por el formulario VIN1).
     * Devuelve sus datos para precargarlos y permitir su edición.
     */
    public function buscarCliente(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tipo_documento' => ['required', 'string', 'max:255'],
            'numero_documento' => ['required', 'string', 'max:20'],
        ]);

        $cliente = Cliente::where('tipo_documento', $data['tipo_documento'])
            ->where('numero_documento', $data['numero_documento'])
            ->first();

        return response()->json([
            'encontrado' => (bool) $cliente,
            'cliente' => $cliente ? [
                'nombres_apellidos' => $cliente->nombres_apellidos,
                'celular' => $cliente->celular,
                'correo' => $cliente->correo,
            ] : null,
        ]);
    }

    public function storeVin1(StoreVin1Request $request): RedirectResponse
    {
        // Reutiliza el cliente si ya existe (mismo tipo + número de documento);
        // si llegan datos modificados, los actualiza. Evita registrar duplicados.
        $cliente = Cliente::updateOrCreate(
            $request->only(['tipo_documento', 'numero_documento']),
            [
                ...$request->only(['nombres_apellidos', 'celular', 'correo']),
                'acepta_promociones' => $request->boolean('acepta_promociones'),
            ],
        );

        $esOtros = $request->modelo_moto_id === 'otros';

        Motocicleta::create([
            'cliente_id' => $cliente->id,
            'modelo_moto_id' => $esOtros ? null : $request->modelo_moto_id,
            'modelo_moto_otro' => $esOtros ? $request->modelo_moto_otro : null,
            'tipo_formulario' => 'VIN1',
            'vin_descripcion' => $request->vin_descripcion,
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

        // Reutiliza el cliente si ya existe (mismo tipo + número de documento);
        // si llegan datos modificados, los actualiza. Evita registrar duplicados.
        $cliente = Cliente::updateOrCreate(
            $request->only(['tipo_documento', 'numero_documento']),
            [
                ...$request->only(['nombres_apellidos', 'celular', 'correo']),
                'acepta_promociones' => $request->boolean('acepta_promociones'),
            ],
        );

        $esOtros = $request->modelo_moto_id === 'otros';

        Motocicleta::create([
            'cliente_id' => $cliente->id,
            'modelo_moto_id' => $esOtros ? null : $request->modelo_moto_id,
            'modelo_moto_otro' => $esOtros ? $request->modelo_moto_otro : null,
            'anio_modelo' => $request->anio_modelo,
            'tipo_formulario' => 'VIN2',
            'vin_descripcion' => $request->vin_descripcion,
            'vin_imagen' => $path,
        ]);

        return redirect()->route('vin')
            ->with('success', '¡Registro exitoso! Tu información ha sido guardada correctamente.');
    }
}
