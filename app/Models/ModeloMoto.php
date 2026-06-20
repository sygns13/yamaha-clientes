<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloMoto extends Model
{
    use HasFactory;

    protected $table = 'modelos_moto';

    protected $fillable = ['nombre', 'descripcion', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function motocicletas()
    {
        return $this->hasMany(Motocicleta::class);
    }
}
