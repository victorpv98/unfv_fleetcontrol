<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleMantenimiento extends Model
{
    protected $table = 'detalles_mantenimiento';
    protected $fillable = ['orden_mantenimiento_id', 'repuesto_id', 'cantidad_utilizada', 'precio_unitario', 'subtotal', 'tipo_costo', 'descripcion'];
    protected $casts = ['cantidad_utilizada' => 'decimal:2', 'precio_unitario' => 'decimal:2', 'subtotal' => 'decimal:2'];

    // Relaciones
    public function ordenMantenimiento() { return $this->belongsTo(OrdenMantenimiento::class); }
    public function repuesto() { return $this->belongsTo(Repuesto::class); }

    // Scopes
    public function scopePorTipo($query, $tipo) { return $query->where('tipo_costo', $tipo); }
    public function scopeRepuestos($query) { return $query->where('tipo_costo', 'repuesto'); }
    public function scopeManoObra($query) { return $query->where('tipo_costo', 'mano_obra'); }

    // Accessors
    public function getTipoCostoBadgeAttribute() { return ['repuesto' => 'primary', 'mano_obra' => 'info', 'servicio_externo' => 'warning'][$this->tipo_costo] ?? 'secondary'; }

    // Boot method para calcular subtotal automáticamente
    protected static function boot() { parent::boot(); static::saving(function ($detalle) { $detalle->subtotal = $detalle->cantidad_utilizada * $detalle->precio_unitario; }); }
}
