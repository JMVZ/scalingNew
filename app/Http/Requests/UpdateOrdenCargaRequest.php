<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdenCargaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente_id' => 'sometimes|required|exists:clientes,id',
            'tipo_servicio' => 'sometimes|required|string|max:255',
            'ruta' => 'nullable|string|max:255',
            'tarifa' => 'sometimes|required|numeric|min:0',
            'moneda' => 'sometimes|required|in:MXN,USD',
            'origen' => 'sometimes|required|string|max:255',
            'destino' => 'sometimes|required|string|max:255',
            'unidad_texto' => 'nullable|string|max:255',
            'placas_unidad' => 'nullable|string|max:50',
            'operador_texto' => 'nullable|string|max:255',
            'licencia_operador' => 'nullable|string|max:50',
            'remolque' => 'nullable|string|max:255',
            'placas_remolque' => 'nullable|string|max:50',
            'descripcion_mercancia' => 'nullable|string',
            'estado' => 'sometimes|in:planeacion,en_ejecucion,finalizado',
        ];
    }

    /**
     * Mensajes de validación personalizados
     */
    public function messages(): array
    {
        return [
            'cliente_id.required' => 'El cliente es obligatorio',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'tipo_servicio.required' => 'El tipo de servicio es obligatorio',
            'tarifa.required' => 'La tarifa es obligatoria',
            'tarifa.numeric' => 'La tarifa debe ser un número',
            'tarifa.min' => 'La tarifa debe ser mayor o igual a 0',
            'moneda.required' => 'La moneda es obligatoria',
            'moneda.in' => 'La moneda debe ser MXN o USD',
            'origen.required' => 'El origen es obligatorio',
            'destino.required' => 'El destino es obligatorio',
        ];
    }
}
