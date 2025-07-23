<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoVehicular extends Model
{
    protected $table = 'movimientos_vehiculares';
    protected $fillable = ['vehiculo_id', 'conductor_id', 'destino_id', 'formulario_ma122', 'fecha_hora_salida', 'fecha_hora_entrada', 'kilometraje_salida', 'kilometraje_entrada', 'kilometros_recorridos', 'combustible_inicial', 'combustible_abastecido', 'combustible_final', 'proposito_viaje', 'observaciones_salida', 'observaciones_entrada', 'estado', 'autorizado_por'];
    protected $dates = ['fecha_hora_salida', 'fecha_hora_entrada'];
    protected $casts = ['fecha_hora_salida' => 'datetime', 'fecha_hora_entrada' => 'datetime', 'kilometraje_salida' => 'integer', 'kilometraje_entrada' => 'integer', 'kilometros_recorridos' => 'integer', 'combustible_inicial' => 'decimal:2', 'combustible_abastecido' => 'decimal:2', 'combustible_final' => 'decimal:2'];

    // Relaciones
    public function vehiculo() { return $this->belongsTo(Vehiculo::class); }
    public function conductor() { return $this->belongsTo(Conductor::class); }
    public function destino() { return $this->belongsTo(Destino::class); }
    public function autorizadoPor() { return $this->belongsTo(User::class, 'autorizado_por'); }
    public function inspecciones() { return $this->hasMany(InspeccionVehicular::class, 'movimiento_id'); }
    public function inspeccionSalida() { return $this->hasOne(InspeccionVehicular::class, 'movimiento_id')->where('tipo_inspeccion', 'salida'); }
    public function inspeccionEntrada() { return $this->hasOne(InspeccionVehicular::class, 'movimiento_id')->where('tipo_inspeccion', 'entrada'); }

    // Scopes
    public function scopeEnCurso($query) { return $query->where('estado', 'en_curso'); }
    public function scopeFinalizados($query) { return $query->where('estado', 'finalizado'); }
    public function scopePorVehiculo($query, $vehiculoId) { return $query->where('vehiculo_id', $vehiculoId); }
    public function scopePorConductor($query, $conductorId) { return $query->where('conductor_id', $conductorId); }
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin) { return $query->whereBetween('fecha_hora_salida', [$fechaInicio, $fechaFin]); }

    // Accessors
    public function getDuracionAttribute() { return $this->fecha_hora_entrada ? $this->fecha_hora_salida->diffForHumans($this->fecha_hora_entrada, true) : null; }
    public function getTiempoTranscurridoAttribute() { return $this->estado === 'en_curso' ? $this->fecha_hora_salida->diffForHumans(now(), true) : null; }
    public function getConsumoPromedioAttribute() { return ($this->kilometros_recorridos && $this->combustible_abastecido) ? round(($this->combustible_abastecido / $this->kilometros_recorridos) * 100, 2) : null; }
    public function getEstadoBadgeAttribute() { return ['en_curso' => 'primary', 'finalizado' => 'success', 'cancelado' => 'danger'][$this->estado] ?? 'secondary'; }

    protected static function boot() { parent::boot(); static::creating(function ($mov) { if (!$mov->formulario_ma122) $mov->formulario_ma122 = 'MA122-' . date('Y') . '-' . str_pad(static::whereYear('created_at', date('Y'))->count() + 1, 4, '0', STR_PAD_LEFT); }); }
}
