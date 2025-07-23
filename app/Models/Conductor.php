<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{

    protected $table = 'conductores';
    protected $fillable = ['dni', 'nombres', 'apellidos', 'telefono', 'email', 'direccion', 'fecha_nacimiento', 'licencia_numero', 'licencia_categoria', 'licencia_vencimiento', 'certificaciones', 'estado'];
    protected $dates = ['fecha_nacimiento', 'licencia_vencimiento', 'deleted_at'];
    protected $casts = ['fecha_nacimiento' => 'date', 'licencia_vencimiento' => 'date'];

    // Relaciones
    public function asignaciones() { return $this->hasMany(AsignacionVehicular::class); }
    public function asignacionActiva() { return $this->hasOne(AsignacionVehicular::class)->where('estado', 'activa'); }
    public function vehiculoActual() { return $this->hasOneThrough(Vehiculo::class, AsignacionVehicular::class, 'conductor_id', 'id', 'id', 'vehiculo_id')->where('asignaciones_vehiculares.estado', 'activa'); }
    public function movimientos() { return $this->hasMany(MovimientoVehicular::class); }
    public function movimientoActivo() { return $this->hasOne(MovimientoVehicular::class)->where('estado', 'en_curso'); }
    public function alertas() { return $this->morphMany(Alerta::class, 'entidad'); }

    // Scopes
    public function scopeActivos($query) { return $query->where('estado', 'activo'); }
    public function scopeDisponibles($query) { return $query->where('estado', 'activo')->whereDoesntHave('movimientoActivo'); }
    public function scopeConLicenciaVigente($query) { return $query->where('licencia_vencimiento', '>', now()); }
    public function scopePorCategoria($query, $categoria) { return $query->where('categoria', $categoria); }

    // Accessors
    public function getEstadoStockAttribute() { return $this->stock_actual <= $this->stock_minimo ? 'bajo' : ($this->stock_actual > $this->stock_minimo * 2 ? 'alto' : 'normal'); }
    public function getEstadoStockBadgeAttribute() { return ['bajo' => 'danger', 'normal' => 'success', 'alto' => 'info'][$this->estado_stock] ?? 'secondary'; }
    public function getValorInventarioAttribute() { return $this->stock_actual * $this->precio_unitario; }

    // Mutators
    public function setCodigoAttribute($value) { $this->attributes['codigo'] = strtoupper($value); }
    public function setNombreAttribute($value) { $this->attributes['nombre'] = ucwords(strtolower($value)); }
}
