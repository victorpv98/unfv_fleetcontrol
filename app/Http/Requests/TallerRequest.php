<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TallerRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['nombre' => 'required|string|max:100', 'tipo' => 'required|in:propio,externo', 'ruc' => 'nullable|string|size:11|unique:talleres,ruc,' . $this->taller?->id, 'direccion' => 'required|string|max:200', 'telefono' => 'nullable|string|max:15', 'email' => 'nullable|email|max:100', 'contacto_nombre' => 'nullable|string|max:100', 'especialidades' => 'nullable|string|max:500']; }
}
