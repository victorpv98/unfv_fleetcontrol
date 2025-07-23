<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class FacturaRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['orden_mantenimiento_id' => 'required|exists:ordenes_mantenimiento,id', 'taller_id' => 'required|exists:talleres,id', 'numero_factura' => 'required|string|max:20|unique:facturas_mantenimiento,numero_factura,' . $this->factura?->id, 'fecha_factura' => 'required|date|before_or_equal:today', 'monto_total' => 'required|numeric|min:0', 'descripcion' => 'nullable|string|max:500']; }
}
