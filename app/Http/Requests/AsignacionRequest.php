<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class AsignacionRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['vehiculo_id' => 'required|exists:vehiculos,id', 'conductor_id' => 'required|exists:conductores,id', 'fecha_asignacion' => 'required|date|before_or_equal:today', 'observaciones' => 'nullable|string|max:500']; }
}
