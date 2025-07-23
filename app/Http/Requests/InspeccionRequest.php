<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class InspeccionRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['movimiento_id' => 'required|exists:movimientos_vehiculares,id', 'tipo_inspeccion' => 'required|in:salida,entrada', 'nivel_combustible' => 'required|in:lleno,3/4,1/2,1/4,vacio', 'estado_neumaticos' => 'required|in:excelente,bueno,regular,malo', 'luces_funcionando' => 'required|boolean', 'frenos_funcionando' => 'required|boolean', 'nivel_aceite' => 'required|in:alto,normal,bajo', 'limpieza_vehiculo' => 'required|in:excelente,bueno,regular,malo', 'documentos_vehiculo' => 'required|boolean', 'kit_emergencia' => 'required|boolean', 'observaciones' => 'nullable|string|max:500']; }
}
