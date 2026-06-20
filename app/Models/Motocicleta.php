<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motocicleta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'modelo_moto_id',
        'modelo_moto_otro',
        'anio_modelo',
        'tipo_formulario',
        'vin_descripcion',
        'vin_imagen',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function modeloMoto()
    {
        return $this->belongsTo(ModeloMoto::class);
    }
}
