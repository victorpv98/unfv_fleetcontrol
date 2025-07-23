<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $fillable = ['orden_mantenimiento_id', 'taller_id', 'numero_cotizacion', 'descripcion_servicios', 'costo_mano_obra', 'costo_repuestos', 'costo_total', 'tiempo_estimado', 'fecha_cotizacion', 'fecha_validez', 'estado', 'observaciones'];
    protected $dates = ['fecha_cotizacion', 'fecha_validez'];
    protected $casts = ['fecha_cotizacion' => 'date', 'fecha_validez' => 'date', 'costo_mano_obra' => 'decimal:2', 'costo_repuestos' => 'decimal:2', 'costo_total' => 'decimal:2', 'tiempo_estimado' => 'integer'];

    // Relaciones
    public function ordenMantenimiento() { return $this->belongsTo(OrdenMantenimiento::class); }
    public function taller() { return $this->belongsTo(Taller::class); }

    // Scopes
    public function scopeVigentes($query) { return $query->where('fecha_validez', '>=', now()); }
    public function scopeVencidas($query) { return $query->where('fecha_validez', '<', now()); }
    public function scopePorEstado($query, $estado) { return $query->where('estado', $estado); }

    // Accessors
    public function getEstadoBadgeAttribute() { return ['pendiente' => 'warning', 'aprobada' => 'success', 'rechazada' => 'danger', 'vencida' => 'secondary'][$this->estado] ?? 'secondary'; }
    public function getVigenciaAttribute() { return $this->fecha_validez >= now(); }
    public function getDiasVigenciaAttribute() { return $this->fecha_validez->diffInDays(now(), false); }
}
