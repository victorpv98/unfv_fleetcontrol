<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class DestinoRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['nombre' => 'required|string|max:100', 'direccion' => 'required|string|max:200', 'distrito' => 'required|string|max:50', 'provincia' => 'required|string|max:50', 'departamento' => 'required|string|max:50', 'coordenadas' => 'nullable|string|max:50', 'descripcion' => 'nullable|string|max:500']; }
}
