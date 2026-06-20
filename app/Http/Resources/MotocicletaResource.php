<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Transforma el modelo Motocicleta para la tabla de Formularios VIN.
 * Incluye relaciones de cliente y modelo, y construye la URL pública de la imagen.
 */
class MotocicletaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'tipo_formulario' => $this->tipo_formulario,
            'vin_descripcion' => $this->vin_descripcion,
            // URL pública generada solo si existe el archivo guardado
            'vin_imagen_url'  => $this->vin_imagen
                ? Storage::disk('public')->url($this->vin_imagen)
                : null,
            // Fecha y hora formateada para mostrar en tabla
            'created_at'      => $this->created_at->format('d/m/Y H:i'),

            // Datos del cliente aplanados (evitar objeto anidado complejo)
            'cliente' => [
                'nombres_apellidos' => $this->cliente->nombres_apellidos,
                'celular'           => $this->cliente->celular,
                'correo'            => $this->cliente->correo,
            ],

            // Cuando el cliente eligió "OTROS", modeloMoto es null
            'modelo_moto' => [
                'nombre' => $this->modeloMoto
                    ? $this->modeloMoto->nombre
                    : 'OTROS: ' . $this->modelo_moto_otro,
            ],
        ];
    }
}
