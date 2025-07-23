<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{

    protected $table = 'destinos';
    protected $fillable = ['nombre', 'direccion', 'distrito', 'provincia', 'departamento', 'coordenadas', 'descripcion', 'activo'];
    protected $casts = ['activo' => 'boolean'];

    // Relaciones
    public function movimientos() { return $this->hasMany(MovimientoVehicular::class); }

    // Scopes
    public function scopeActivos($query) { return $query->where('activo', true); }
    public function scopePorDepartamento($query, $departamento) { return $query->where('departamento', $departamento); }
    public function scopePorProvincia($query, $provincia) { return $query->where('provincia', $provincia); }

    // Accessors
    public function getUbicacionCompletaAttribute() { return collect([$this->distrito, $this->provincia, $this->departamento])->filter()->implode(', ') ?: 'No especificado'; }
    public function getDireccionCompletaAttribute() { return $this->direccion . ', ' . $this->ubicacion_completa; }
}
