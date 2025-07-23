<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenMantenimiento extends Model
{
    protected $table = 'ordenes_mantenimiento';
    protected $fillable = ['numero_orden', 'vehiculo_id', 'taller_id', 'tipo_mantenimiento', 'tipo_servicio', 'descripcion_trabajo', 'fecha_solicitud', 'fecha_programada', 'fecha_inicio', 'fecha_finalizacion', 'kilometraje_servicio', 'estado', 'prioridad', 'costo_estimado', 'costo_real', 'observaciones', 'solicitado_por', 'aprobado_por'];
    protected $dates = ['fecha_solicitud', 'fecha_programada', 'fecha_inicio', 'fecha_finalizacion'];
    protected $casts = ['fecha_solicitud' => 'date', 'fecha_programada' => 'date', 'fecha_inicio' => 'date', 'fecha_finalizacion' => 'date', 'kilometraje_servicio' => 'integer', 'costo_estimado' => 'decimal:2', 'costo_real' => 'decimal:2'];

    // Relaciones
    public function vehiculo() { return $this->belongsTo(Vehiculo::class); }
    public function taller() { return $this->belongsTo(Taller::class); }
    public function solicitadoPor() { return $this->belongsTo(User::class, 'solicitado_por'); }
    public function aprobadoPor() { return $this->belongsTo(User::class, 'aprobado_por'); }
    public function cotizaciones() { return $this->hasMany(Cotizacion::class); }
    public function detalles() { return $this->hasMany(DetalleMantenimiento::class); }
    public function facturas() { return $this->hasMany(FacturaMantenimiento::class); }

    // Scopes
    public function scopePendientes($query) { return $query->whereIn('estado', ['solicitada', 'cotizando', 'aprobada']); }
    public function scopeEnProceso($query) { return $query->where('estado', 'en_proceso'); }
    public function scopeFinalizadas($query) { return $query->where('estado', 'finalizada'); }
    public function scopePorPrioridad($query, $prioridad) { return $query->where('prioridad', $prioridad); }
    public function scopePorTipo($query, $tipo) { return $query->where('tipo_mantenimiento', $tipo); }

    // Accessors
    public function getEstadoBadgeAttribute() { return ['solicitada' => 'warning', 'cotizando' => 'info', 'aprobada' => 'primary', 'en_proceso' => 'warning', 'finalizada' => 'success', 'cancelada' => 'danger'][$this->estado] ?? 'secondary'; }
    public function getPrioridadBadgeAttribute() { return ['baja' => 'secondary', 'media' => 'primary', 'alta' => 'warning', 'critica' => 'danger'][$this->prioridad] ?? 'secondary'; }
    public function getDuracionAttribute() { return ($this->fecha_inicio && $this->fecha_finalizacion) ? $this->fecha_inicio->diffInDays($this->fecha_finalizacion) : null; }

    protected static function boot() { parent::boot(); static::creating(function ($orden) { if (!$orden->numero_orden) $orden->numero_orden = 'OM-' . date('Y') . '-' . str_pad(static::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT); }); }
}
