<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class DocumentoRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['tipo_documento' => 'required|in:soat,revision_tecnica,tarjeta_propiedad,permiso_circulacion', 'numero_documento' => 'required|string|max:30', 'fecha_emision' => 'required|date|before_or_equal:today', 'fecha_vencimiento' => 'required|date|after:fecha_emision', 'entidad_emisora' => 'required|string|max:100', 'archivo_url' => 'nullable|string|max:255']; }
}
