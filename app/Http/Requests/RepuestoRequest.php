<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class RepuestoRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['codigo' => 'required|string|max:20|unique:repuestos,codigo,' . $this->repuesto?->id, 'nombre' => 'required|string|max:100', 'descripcion' => 'nullable|string|max:500', 'marca' => 'nullable|string|max:50', 'categoria' => 'required|string|max:50', 'unidad_medida' => 'required|in:unidad,litro,kilo,metro,caja,par', 'stock_minimo' => 'required|integer|min:1', 'stock_actual' => 'required|integer|min:0', 'precio_unitario' => 'nullable|numeric|min:0']; }
}
