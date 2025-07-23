<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class MantenimientoRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['vehiculo_id' => 'required|exists:vehiculos,id', 'taller_id' => 'nullable|exists:talleres,id', 'tipo_mantenimiento' => 'required|in:preventivo,correctivo,emergencia', 'tipo_servicio' => 'required|in:propio,externo', 'descripcion_trabajo' => 'required|string|max:1000', 'fecha_programada' => 'nullable|date|after_or_equal:today', 'kilometraje_servicio' => 'required|integer|min:0', 'prioridad' => 'required|in:baja,media,alta,critica', 'costo_estimado' => 'nullable|numeric|min:0']; }
}
