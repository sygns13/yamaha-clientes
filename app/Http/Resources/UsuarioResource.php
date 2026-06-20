<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforma el modelo User para su uso en Inertia/API.
 * Añade el flag is_self para que el frontend sepa cuál es el usuario autenticado.
 */
class UsuarioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'role'       => $this->role,
            'activo'     => $this->activo,
            // Fecha formateada en español: dd/mm/yyyy
            'created_at' => $this->created_at->format('d/m/Y'),
            // Identifica al usuario autenticado para proteger sus propias acciones
            'is_self'    => $this->id === $request->user()?->id,
        ];
    }
}
