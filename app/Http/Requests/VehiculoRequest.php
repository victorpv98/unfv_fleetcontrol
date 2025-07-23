<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class VehiculoRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['placa' => 'required|string|max:8|unique:vehiculos,placa,' . $this->vehiculo?->id, 'marca' => 'required|string|max:50', 'modelo' => 'required|string|max:50', 'año' => 'required|integer|min:1990|max:' . (date('Y') + 1), 'tipo_combustible' => 'required|in:gasolina,diesel,gas,electrico,hibrido', 'tipo_vehiculo' => 'required|in:automovil,camioneta,camion,bus,moto', 'color' => 'required|string|max:30', 'numero_motor' => 'nullable|string|max:50', 'numero_chasis' => 'nullable|string|max:50', 'capacidad_tanque' => 'required|numeric|min:1', 'kilometraje_actual' => 'required|integer|min:0', 'fecha_adquisicion' => 'required|date|before_or_equal:today']; }
    public function messages() { return ['placa.unique' => 'Ya existe un vehículo con esta placa.', 'año.max' => 'El año no puede ser mayor al año próximo.']; }
}
