<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaMantenimiento extends Model
{
    protected $table = 'facturas_mantenimiento';
    protected $fillable = ['orden_mantenimiento_id', 'taller_id', 'numero_factura', 'ruc_emisor', 'fecha_emision', 'fecha_vencimiento', 'subtotal', 'igv', 'total', 'estado', 'archivo_url', 'observaciones'];
    protected $dates = ['fecha_emision', 'fecha_vencimiento'];
    protected $casts = ['fecha_emision' => 'date', 'fecha_vencimiento' => 'date', 'subtotal' => 'decimal:2', 'igv' => 'decimal:2', 'total' => 'decimal:2'];

    // Relaciones
    public function ordenMantenimiento() { return $this->belongsTo(OrdenMantenimiento::class); }
    public function taller() { return $this->belongsTo(Taller::class); }

    // Scopes
    public function scopePendientes($query) { return $query->where('estado', 'pendiente'); }
    public function scopePagadas($query) { return $query->where('estado', 'pagada'); }
    public function scopeVencidas($query) { return $query->where('fecha_vencimiento', '<', now())->where('estado', 'pendiente'); }

    // Accessors
    public function getEstadoBadgeAttribute() { return ['pendiente' => 'warning', 'pagada' => 'success', 'anulada' => 'danger'][$this->estado] ?? 'secondary'; }
    public function getDiasVencimientoAttribute() { return $this->fecha_vencimiento ? $this->fecha_vencimiento->diffInDays(now(), false) : null; }
    public function getVencidaAttribute() { return $this->fecha_vencimiento && $this->fecha_vencimiento < now() && $this->estado === 'pendiente'; }

    // Boot method para calcular IGV automáticamente
    protected static function boot() { parent::boot(); static::saving(function ($factura) { if (!$factura->igv) $factura->igv = $factura->subtotal * 0.18; if (!$factura->total) $factura->total = $factura->subtotal + $factura->igv; }); }
}
