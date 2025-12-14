<?php

namespace App\Http\Requests\tiendas;

use Illuminate\Foundation\Http\FormRequest;

class TiendaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|integer',
            'telefono' => 'nullable|integer',
            'ubicacion' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la tienda es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'nit.integer' => 'El NIT debe ser un entero válido.',
            'telefono.integer' => 'El teléfono debe ser un entero válido.',
            'ubicacion.string' => 'La ubicación debe ser un texto válido.',
            'ubicacion.max' => 'La ubicación no puede superar los 255 caracteres.',
        ];
    }
}
