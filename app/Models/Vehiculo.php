<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{

    protected $table = 'vehiculos';
    protected $fillable = ['placa', 'marca', 'modelo', 'año', 'tipo_combustible', 'tipo_vehiculo', 'color', 'numero_motor', 'numero_chasis', 'capacidad_tanque', 'kilometraje_actual', 'codigo_qr', 'estado', 'fecha_adquisicion'];
    protected $dates = ['fecha_adquisicion', 'deleted_at'];
    protected $casts = ['año' => 'integer', 'capacidad_tanque' => 'decimal:2', 'kilometraje_actual' => 'integer', 'fecha_adquisicion' => 'date'];

    // Relaciones
    public function documentos() { return $this->hasMany(DocumentoVehicular::class); }
    public function asignaciones() { return $this->hasMany(AsignacionVehicular::class); }
    public function asignacionActiva() { return $this->hasOne(AsignacionVehicular::class)->where('estado', 'activa'); }
    public function conductorActual() { return $this->hasOneThrough(Conductor::class, AsignacionVehicular::class, 'vehiculo_id', 'id', 'id', 'conductor_id')->where('asignaciones_vehiculares.estado', 'activa'); }
    public function movimientos() { return $this->hasMany(MovimientoVehicular::class); }
    public function movimientoActivo() { return $this->hasOne(MovimientoVehicular::class)->where('estado', 'en_curso'); }
    public function ordenesMantenimiento() { return $this->hasMany(OrdenMantenimiento::class); }
    public function alertas() { return $this->morphMany(Alerta::class, 'entidad'); }

    // Scopes
    public function scopeActivos($query) { return $query->where('estado', 'activo'); }
    public function scopeDisponibles($query) { return $query->where('estado', 'activo')->whereDoesntHave('movimientoActivo'); }
    public function scopePorTipo($query, $tipo) { return $query->where('tipo_vehiculo', $tipo); }
    public function scopeConMantenimientoPendiente($query) { return $query->whereHas('ordenesMantenimiento', fn($q) => $q->whereIn('estado', ['solicitada', 'aprobada', 'en_proceso'])); }

    // Accessors
    public function getDescripcionCompletaAttribute() { return "{$this->marca} {$this->modelo} ({$this->año}) - {$this->placa}"; }
    public function getEstadoBadgeAttribute() { return ['activo' => 'success', 'mantenimiento' => 'warning', 'inactivo' => 'danger'][$this->estado] ?? 'secondary'; }
    public function getRequiereMantenimientoAttribute() { $km = ConfiguracionSistema::where('clave', 'km_mantenimiento_preventivo')->value('valor') ?? 5000; return $this->kilometraje_actual % $km === 0 && $this->kilometraje_actual > 0; }

    // Mutators
    public function setPlacaAttribute($value) { $this->attributes['placa'] = strtoupper($value); }
    public function setMarcaAttribute($value) { $this->attributes['marca'] = ucwords(strtolower($value)); }
    public function setModeloAttribute($value) { $this->attributes['modelo'] = ucwords(strtolower($value)); }
}
