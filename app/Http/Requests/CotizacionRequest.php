<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CotizacionRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['orden_mantenimiento_id' => 'required|exists:ordenes_mantenimiento,id', 'taller_id' => 'required|exists:talleres,id', 'descripcion_servicios' => 'required|string|max:1000', 'costo_mano_obra' => 'required|numeric|min:0', 'costo_repuestos' => 'required|numeric|min:0', 'costo_total' => 'required|numeric|min:0', 'tiempo_estimado' => 'required|integer|min:1', 'fecha_validez' => 'required|date|after:today']; }
}
