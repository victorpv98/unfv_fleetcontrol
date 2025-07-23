<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $table = 'alertas';
    protected $fillable = ['tipo_alerta', 'entidad_id', 'entidad_tipo', 'titulo', 'mensaje', 'fecha_programada', 'fecha_notificacion', 'prioridad', 'estado', 'usuario_asignado'];
    protected $dates = ['fecha_programada', 'fecha_notificacion'];
    protected $casts = ['fecha_programada' => 'date', 'fecha_notificacion' => 'datetime'];

    // Relaciones
    public function entidad() { return $this->morphTo(); }
    public function usuarioAsignado() { return $this->belongsTo(User::class, 'usuario_asignado'); }

    // Scopes
    public function scopePendientes($query) { return $query->where('estado', 'pendiente'); }
    public function scopeNotificadas($query) { return $query->where('estado', 'notificada'); }
    public function scopeLeidas($query) { return $query->where('estado', 'leida'); }
    public function scopeResueltas($query) { return $query->where('estado', 'resuelta'); }
    public function scopePorPrioridad($query, $prioridad) { return $query->where('prioridad', $prioridad); }
    public function scopePorTipo($query, $tipo) { return $query->where('tipo_alerta', $tipo); }
    public function scopeVencidas($query) { return $query->where('fecha_programada', '<=', now()); }

    // Accessors
    public function getEstadoBadgeAttribute() { return ['pendiente' => 'warning', 'notificada' => 'info', 'leida' => 'primary', 'resuelta' => 'success'][$this->estado] ?? 'secondary'; }
    public function getPrioridadBadgeAttribute() { return ['baja' => 'secondary', 'media' => 'primary', 'alta' => 'warning', 'critica' => 'danger'][$this->prioridad] ?? 'secondary'; }
    public function getTipoAlertaFormateadoAttribute() { return ['vencimiento_documento' => 'Vencimiento de Documento', 'mantenimiento_preventivo' => 'Mantenimiento Preventivo', 'inspeccion_tecnica' => 'Inspección Técnica', 'soat' => 'SOAT', 'licencia_conductor' => 'Licencia de Conductor', 'kilometraje' => 'Kilometraje'][$this->tipo_alerta] ?? $this->tipo_alerta; }
    public function getDiasRestantesAttribute() { return $this->fecha_programada->diffInDays(now(), false); }
}