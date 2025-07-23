<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspeccionVehicular extends Model
{
    protected $table = 'inspecciones_vehiculares';
    protected $fillable = ['movimiento_id', 'tipo_inspeccion', 'nivel_combustible', 'estado_neumaticos', 'luces_funcionando', 'frenos_funcionando', 'nivel_aceite', 'limpieza_vehiculo', 'documentos_vehiculo', 'kit_emergencia', 'observaciones', 'inspector_id'];
    protected $casts = ['luces_funcionando' => 'boolean', 'frenos_funcionando' => 'boolean', 'documentos_vehiculo' => 'boolean', 'kit_emergencia' => 'boolean'];

    // Relaciones
    public function movimiento() { return $this->belongsTo(MovimientoVehicular::class); }
    public function inspector() { return $this->belongsTo(User::class, 'inspector_id'); }

    // Scopes
    public function scopePorTipo($query, $tipo) { return $query->where('tipo_inspeccion', $tipo); }
    public function scopeSalida($query) { return $query->where('tipo_inspeccion', 'salida'); }
    public function scopeEntrada($query) { return $query->where('tipo_inspeccion', 'entrada'); }

    // Accessors
    public function getEstadoGeneralAttribute() { $items = [$this->luces_funcionando, $this->frenos_funcionando, $this->documentos_vehiculo, $this->kit_emergencia]; $total = count(array_filter($items, fn($i) => !is_null($i))); $buenos = count(array_filter($items)); return $total > 0 ? ($buenos / $total >= 0.8 ? 'bueno' : ($buenos / $total >= 0.5 ? 'regular' : 'malo')) : 'sin_datos'; }
    public function getEstadoBadgeAttribute() { return ['bueno' => 'success', 'regular' => 'warning', 'malo' => 'danger', 'sin_datos' => 'secondary'][$this->estado_general] ?? 'secondary'; }
}
