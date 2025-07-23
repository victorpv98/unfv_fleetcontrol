<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'apellidos', 'email', 'password', 'rol', 'activo'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed', 'activo' => 'boolean'];

    // Relaciones
    public function movimientosAutorizados() { return $this->hasMany(MovimientoVehicular::class, 'autorizado_por'); }
    public function ordenesSolicitadas() { return $this->hasMany(OrdenMantenimiento::class, 'solicitado_por'); }
    public function ordenesAprobadas() { return $this->hasMany(OrdenMantenimiento::class, 'aprobado_por'); }
    public function alertasAsignadas() { return $this->hasMany(Alerta::class, 'usuario_asignado'); }
    public function inspecciones() { return $this->hasMany(InspeccionVehicular::class, 'inspector_id'); }
    public function auditoria() { return $this->hasMany(Auditoria::class, 'usuario_id'); }

    // Accessors
    public function getNombreCompletoAttribute() { return trim($this->name . ' ' . $this->apellidos); }

    // Scopes
    public function scopeActivos($query) { return $query->where('activo', true); }
    public function scopePorRol($query, $rol) { return $query->where('rol', $rol); }

    // Métodos auxiliares
    public function esAdministrador() { return $this->rol === 'administrador'; }
    public function puedeGestionarVehiculos() { return in_array($this->rol, ['administrador', 'operador']); }
    public function puedeGestionarMantenimiento() { return in_array($this->rol, ['administrador', 'jefe_mantenimiento']); }
    public function puedeControlarMovimientos() { return in_array($this->rol, ['administrador', 'operador', 'encargado_garaje']); }
}