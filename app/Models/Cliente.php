<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombres_apellidos', 'celular', 'correo'];

    public function motocicletas()
    {
        return $this->hasMany(Motocicleta::class);
    }
}
