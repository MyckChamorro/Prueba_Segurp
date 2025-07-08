<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('docente');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estudiante_id' => 'required|exists:users,id',
            'asignatura_id' => 'required|exists:asignaturas,id',
            'nota_1' => 'nullable|numeric|min:0|max:20',
            'nota_2' => 'nullable|numeric|min:0|max:20',
            'nota_3' => 'nullable|numeric|min:0|max:20',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'estudiante_id.required' => 'Debe seleccionar un estudiante.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            'asignatura_id.required' => 'Debe seleccionar una asignatura.',
            'asignatura_id.exists' => 'La asignatura seleccionada no existe.',
            'nota_1.numeric' => 'La nota 1 debe ser un número.',
            'nota_1.min' => 'La nota 1 debe ser mayor o igual a 0.',
            'nota_1.max' => 'La nota 1 debe ser menor o igual a 20.',
            'nota_2.numeric' => 'La nota 2 debe ser un número.',
            'nota_2.min' => 'La nota 2 debe ser mayor o igual a 0.',
            'nota_2.max' => 'La nota 2 debe ser menor o igual a 20.',
            'nota_3.numeric' => 'La nota 3 debe ser un número.',
            'nota_3.min' => 'La nota 3 debe ser mayor o igual a 0.',
            'nota_3.max' => 'La nota 3 debe ser menor o igual a 20.',
        ];
    }
}
