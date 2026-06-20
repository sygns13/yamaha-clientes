<?php

namespace App\Http\Controllers\Admin;

use App\Exports\FormulariosVinExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ModeloMotoResource;
use App\Http\Resources\MotocicletaResource;
use App\Models\ModeloMoto;
use App\Models\Motocicleta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FormulariosVinController extends Controller
{
    /**
     * Lista paginada de formularios VIN con filtros opcionales.
     * Usa MotocicletaResource para transformar los datos de forma estandarizada.
     */
    public function index(Request $request): Response
    {
        // Extraer solo los filtros permitidos
        $filtros = $request->only(['tipo', 'nombres', 'celular', 'modelo', 'vin']);

        // Cantidad de filas por página (lista blanca de valores permitidos)
        $perPage = in_array((int) $request->input('per_page', 15), [10, 15, 25, 50, 100])
            ? (int) $request->input('per_page', 15)
            : 15;

        $registros = Motocicleta::with(['cliente', 'modeloMoto'])
            // Filtro por tipo de formulario (VIN1 / VIN2)
            ->when($filtros['tipo']    ?? null, fn ($q, $v) => $q->where('tipo_formulario', $v))
            // Filtro por nombre del cliente (búsqueda parcial)
            ->when($filtros['nombres'] ?? null, fn ($q, $v) => $q->whereHas('cliente',
                fn ($q2) => $q2->where('nombres_apellidos', 'like', "%{$v}%")
            ))
            // Filtro por celular del cliente
            ->when($filtros['celular'] ?? null, fn ($q, $v) => $q->whereHas('cliente',
                fn ($q2) => $q2->where('celular', 'like', "%{$v}%")
            ))
            // Filtro por modelo: 'otros' = registros sin FK, el resto por ID
            ->when($filtros['modelo'] ?? null, function ($q, $v) {
                $v === 'otros'
                    ? $q->whereNull('modelo_moto_id')->whereNotNull('modelo_moto_otro')
                    : $q->where('modelo_moto_id', $v);
            })
            // Filtro por código VIN (búsqueda parcial)
            ->when($filtros['vin']     ?? null, fn ($q, $v) => $q->where('vin_descripcion', 'like', "%{$v}%"))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();  // Preserva los filtros en los links de paginación

        return Inertia::render('Admin/FormulariosVin/Index', [
            // Resource::collection sobre paginator conserva links y meta de paginación
            'registros' => MotocicletaResource::collection($registros),
            // Solo modelos activos para el filtro de la tabla
            'modelos'   => ModeloMotoResource::collection(ModeloMoto::orderBy('nombre')->get()),
            'filtros'   => $filtros,
            'perPage'   => $perPage,
        ]);
    }

    /**
     * Elimina un registro y su imagen asociada del storage.
     */
    public function destroy(Motocicleta $motocicleta): \Illuminate\Http\RedirectResponse
    {
        // Eliminar imagen del disco público si existe
        if ($motocicleta->vin_imagen) {
            Storage::disk('public')->delete($motocicleta->vin_imagen);
        }

        $motocicleta->delete();

        return back()->with('success', 'Registro eliminado correctamente.');
    }

    /**
     * Exporta los registros filtrados a un archivo Excel.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filtros  = $request->only(['tipo', 'nombres', 'celular', 'modelo', 'vin']);
        $filename = 'formularios-vin-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new FormulariosVinExport($filtros), $filename);
    }
}
