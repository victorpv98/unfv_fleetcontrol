<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{

    protected $table = 'repuestos';
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'marca', 'categoria', 'unidad_medida', 'stock_minimo', 'stock_actual', 'precio_unitario', 'activo'];
    protected $casts = ['stock_minimo' => 'integer', 'stock_actual' => 'integer', 'precio_unitario' => 'decimal:2', 'activo' => 'boolean'];

    // Relaciones
    public function detallesMantenimiento() { return $this->hasMany(DetalleMantenimiento::class); }

    // Scopes
    public function scopeActivos($query) { return $query->where('activo', true); }
    public function scopeConStock($query) { return $query->where('stock_actual', '>', 0); }
    public function scopeStockBajo($query) { return $query->whereColumn('stock_actual', '<=', 'stock_minimo'); }
    public function scopePorCategoria($query, $categoria) { return $query->where('categoria', $categoria); }
    public function scopePorMarca($query, $marca) { return $query->where('marca', $marca); }
    public function scopeDisponibles($query) { return $query->where('activo', true)->where('stock_actual', '>', 0); }

    // Accessors
    public function getEstadoStockAttribute() { return $this->stock_actual <= $this->stock_minimo ? 'bajo' : ($this->stock_actual > $this->stock_minimo * 2 ? 'alto' : 'normal'); }
    public function getEstadoStockBadgeAttribute() { return ['bajo' => 'danger', 'normal' => 'success', 'alto' => 'info'][$this->estado_stock] ?? 'secondary'; }
    public function getValorInventarioAttribute() { return $this->stock_actual * ($this->precio_unitario ?? 0); }
    public function getPorcentajeStockAttribute() { return $this->stock_minimo > 0 ? round(($this->stock_actual / $this->stock_minimo) * 100, 1) : 100; }
    public function getDescripcionCompletaAttribute() { return $this->nombre . ($this->marca ? " - {$this->marca}" : '') . " ({$this->codigo})"; }

    // Mutators
    public function setCodigoAttribute($value) { $this->attributes['codigo'] = strtoupper(trim($value)); }
    public function setNombreAttribute($value) { $this->attributes['nombre'] = ucwords(strtolower(trim($value))); }
    public function setMarcaAttribute($value) { $this->attributes['marca'] = $value ? ucwords(strtolower(trim($value))) : null; }
    public function setCategoriaAttribute($value) { $this->attributes['categoria'] = $value ? ucwords(strtolower(trim($value))) : null; }

    // Métodos auxiliares
    public function ajustarStock($cantidad, $motivo = null) { $this->stock_actual += $cantidad; $this->save(); return $this; }
    public function consumir($cantidad) { if ($this->stock_actual >= $cantidad) { $this->stock_actual -= $cantidad; $this->save(); return true; } return false; }
    public function reabastecer($cantidad) { $this->stock_actual += abs($cantidad); $this->save(); return $this; }
    public function necesitaReabastecimiento() { return $this->stock_actual <= $this->stock_minimo; }
}