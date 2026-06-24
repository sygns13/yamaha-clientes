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
            'tipo_documento' => ['required', 'string', 'in:DNI,Carnet de extranjería,RUC,Pasaporte,Otros tipos de documento'],
            'numero_documento' => array_merge(['required', 'string'], $this->numeroDocumentoSizeRules()),
            'nombres_apellidos' => ['required', 'string', 'max:255'],
            'celular' => ['required', 'string', 'max:20', 'regex:/^[0-9+\s\-\(\)]{6,20}$/'],
            'correo' => ['required', 'email', 'max:255'],
            'modelo_moto_id' => $esOtros
                ? ['required', 'in:otros']
                : ['required', 'integer', 'exists:modelos_moto,id'],
            'modelo_moto_otro' => $esOtros
                ? ['required', 'string', 'max:100']
                : ['nullable', 'string', 'max:100'],
            'vin_descripcion' => ['required', 'string', 'max:100'],
            'acepta_privacidad' => ['accepted'],
            'acepta_promociones' => ['boolean'],
        ];
    }

    /**
     * Reglas de longitud para el número de documento según el tipo seleccionado.
     * - DNI: exactamente 8 dígitos (solo números).
     * - RUC: exactamente 11 dígitos (solo números).
     * - Carnet de extranjería / Pasaporte / Otros: máximo 20 caracteres.
     */
    protected function numeroDocumentoSizeRules(): array
    {
        return match ($this->input('tipo_documento')) {
            'DNI' => ['digits:8'],
            'RUC' => ['digits:11'],
            default => ['max:20'],
        };
    }

    public function messages(): array
    {
        return [
            'tipo_documento.required' => 'Seleccione el tipo de documento de identidad.',
            'tipo_documento.in' => 'El tipo de documento seleccionado no es válido.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.digits' => 'El número de documento debe tener exactamente :digits dígitos numéricos.',
            'numero_documento.max' => 'El número de documento no puede superar los :max caracteres.',
            'nombres_apellidos.required' => 'El nombre y apellidos son obligatorios.',
            'celular.required' => 'El celular es obligatorio.',
            'celular.regex' => 'El celular solo puede contener números, +, espacios, guiones y paréntesis.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Ingrese un correo electrónico válido.',
            'modelo_moto_id.required' => 'Seleccione un modelo de moto.',
            'modelo_moto_id.exists' => 'El modelo seleccionado no es válido.',
            'modelo_moto_otro.required' => 'Escribe el modelo de tu moto.',
            'modelo_moto_otro.max' => 'El modelo no puede superar los 100 caracteres.',
            'vin_descripcion.required' => 'El código VIN es obligatorio.',
            'vin_descripcion.max' => 'El código VIN no puede superar los 100 caracteres.',
            'acepta_privacidad.accepted' => 'Debe aceptar la Política de Privacidad para continuar.',
        ];
    }
}
