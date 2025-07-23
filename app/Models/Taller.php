<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    protected $table = 'talleres';
    protected $fillable = ['nombre', 'tipo', 'ruc', 'direccion', 'telefono', 'email', 'contacto_nombre', 'especialidades', 'activo'];
    protected $casts = ['activo' => 'boolean'];

    // Relaciones
    public function ordenesMantenimiento() { return $this->hasMany(OrdenMantenimiento::class); }
    public function cotizaciones() { return $this->hasMany(Cotizacion::class); }
    public function facturas() { return $this->hasMany(FacturaMantenimiento::class); }

    // Scopes
    public function scopeActivos($query) { return $query->where('activo', true); }
    public function scopePropios($query) { return $query->where('tipo', 'propio'); }
    public function scopeExternos($query) { return $query->where('tipo', 'externo'); }

    // Accessors
    public function getTipoBadgeAttribute() { return ['propio' => 'primary', 'externo' => 'info'][$this->tipo] ?? 'secondary'; }
    public function getContactoCompletoAttribute() { return $this->contacto_nombre . ($this->telefono ? " - {$this->telefono}" : '') . ($this->email ? " - {$this->email}" : ''); }
}
