<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ConductorRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['dni' => 'required|string|size:8|unique:conductores,dni,' . $this->conductor?->id, 'nombres' => 'required|string|max:100', 'apellidos' => 'required|string|max:100', 'telefono' => 'nullable|string|max:15', 'email' => 'nullable|email|max:100|unique:conductores,email,' . $this->conductor?->id, 'direccion' => 'nullable|string|max:200', 'fecha_nacimiento' => 'required|date|before:-18 years', 'licencia_numero' => 'required|string|max:15|unique:conductores,licencia_numero,' . $this->conductor?->id, 'licencia_categoria' => 'required|in:A-I,A-IIa,A-IIb,A-IIIa,A-IIIb,A-IIIc', 'licencia_vencimiento' => 'required|date|after:today', 'certificaciones' => 'nullable|string']; }
    public function messages() { return ['dni.size' => 'El DNI debe tener exactamente 8 dígitos.', 'fecha_nacimiento.before' => 'El conductor debe ser mayor de 18 años.', 'licencia_vencimiento.after' => 'La licencia debe estar vigente.']; }
}
