<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class MovimientoRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['vehiculo_id' => 'required|exists:vehiculos,id', 'conductor_id' => 'required|exists:conductores,id', 'destino_id' => 'required|exists:destinos,id', 'fecha_hora_salida' => 'required|date|after_or_equal:now', 'kilometraje_salida' => 'required|integer|min:0', 'combustible_inicial' => 'required|numeric|min:0', 'proposito_viaje' => 'required|string|max:200', 'observaciones_salida' => 'nullable|string|max:500']; }
}

