<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ConfiguracionRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['nombre_sistema' => 'required|string|max:100', 'logo_sistema' => 'nullable|string|max:255', 'alerta_soat_dias' => 'required|integer|min:1|max:365', 'alerta_revision_dias' => 'required|integer|min:1|max:365', 'alerta_mantenimiento_km' => 'required|integer|min:100', 'km_mantenimiento_preventivo' => 'required|integer|min:1000', 'meses_mantenimiento_preventivo' => 'required|integer|min:1|max:12', 'email_notificaciones' => 'required|email', 'telefono_emergencia' => 'nullable|string|max:15']; }
}
