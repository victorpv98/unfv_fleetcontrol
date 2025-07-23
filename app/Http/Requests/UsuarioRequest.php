<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() { return ['name' => 'required|string|max:100', 'apellidos' => 'required|string|max:100', 'email' => 'required|email|max:100|unique:users,email,' . $this->usuario?->id, 'password' => $this->isMethod('post') ? 'required|string|min:6|confirmed' : 'nullable|string|min:6|confirmed', 'rol' => 'required|in:administrador,operador,jefe_mantenimiento,encargado_garaje,supervisor,mecanico,conductor', 'activo' => 'boolean']; }
    public function messages() { return ['password.confirmed' => 'La confirmación de contraseña no coincide.']; }
}
