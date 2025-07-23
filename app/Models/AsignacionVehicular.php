<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionVehicular extends Model
{
    protected $table = 'asignaciones_vehiculares';
    protected $fillable = ['vehiculo_id', 'conductor_id', 'fecha_asignacion', 'fecha_desasignacion', 'estado', 'observaciones'];
    protected $dates = ['fecha_asignacion', 'fecha_desasignacion'];
    protected $casts = ['fecha_asignacion' => 'date', 'fecha_desasignacion' => 'date'];

    // Relaciones
    public function vehiculo() { return $this->belongsTo(Vehiculo::class); }
    public function conductor() { return $this->belongsTo(Conductor::class); }

    // Scopes
    public function scopeActivas($query) { return $query->where('estado', 'activa'); }
    public function scopeFinalizadas($query) { return $query->where('estado', 'finalizada'); }
    public function scopePorVehiculo($query, $vehiculoId) { return $query->where('vehiculo_id', $vehiculoId); }
    public function scopePorConductor($query, $conductorId) { return $query->where('conductor_id', $conductorId); }

    // Accessors
    public function getDuracionAttribute() { return $this->fecha_asignacion->diffInDays($this->fecha_desasignacion ?? now()); }
    public function getEstadoBadgeAttribute() { return ['activa' => 'success', 'finalizada' => 'secondary'][$this->estado] ?? 'secondary'; }
}
