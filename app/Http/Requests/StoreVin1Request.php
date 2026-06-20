<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVin1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $esOtros = $this->input('modelo_moto_id') === 'otros';

        return [
            'nombres_apellidos' => ['required', 'string', 'max:255'],
            'celular'           => ['required', 'string', 'max:20', 'regex:/^[0-9+\s\-\(\)]{6,20}$/'],
            'correo'            => ['required', 'email', 'max:255'],
            'modelo_moto_id'    => $esOtros
                ? ['required', 'in:otros']
                : ['required', 'integer', 'exists:modelos_moto,id'],
            'modelo_moto_otro'  => $esOtros
                ? ['required', 'string', 'max:100']
                : ['nullable', 'string', 'max:100'],
            'vin_descripcion'   => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombres_apellidos.required' => 'El nombre y apellidos son obligatorios.',
            'celular.required'           => 'El celular es obligatorio.',
            'celular.regex'              => 'El celular solo puede contener números, +, espacios, guiones y paréntesis.',
            'correo.required'            => 'El correo electrónico es obligatorio.',
            'correo.email'               => 'Ingrese un correo electrónico válido.',
            'modelo_moto_id.required'    => 'Seleccione un modelo de moto.',
            'modelo_moto_id.exists'      => 'El modelo seleccionado no es válido.',
            'modelo_moto_otro.required'  => 'Escribe el modelo de tu moto.',
            'modelo_moto_otro.max'       => 'El modelo no puede superar los 100 caracteres.',
            'vin_descripcion.required'   => 'El código VIN es obligatorio.',
            'vin_descripcion.max'        => 'El código VIN no puede superar los 100 caracteres.',
        ];
    }
}
