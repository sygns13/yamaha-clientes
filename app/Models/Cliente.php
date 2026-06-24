<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_documento', 'numero_documento', 'nombres_apellidos', 'celular', 'correo', 'acepta_promociones'];

    protected $casts = [
        'acepta_promociones' => 'boolean',
    ];

    public function motocicletas()
    {
        return $this->hasMany(Motocicleta::class);
    }
}
