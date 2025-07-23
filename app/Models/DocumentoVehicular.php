<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoVehicular extends Model
{
    protected $table = 'documentos_vehiculares';
    protected $fillable = ['vehiculo_id', 'tipo_documento', 'numero_documento', 'fecha_emision', 'fecha_vencimiento', 'entidad_emisora', 'archivo_url', 'estado'];
    protected $dates = ['fecha_emision', 'fecha_vencimiento'];
    protected $casts = ['fecha_emision' => 'date', 'fecha_vencimiento' => 'date'];

    // Relaciones
    public function vehiculo() { return $this->belongsTo(Vehiculo::class); }
    public function alertas() { return $this->morphMany(Alerta::class, 'entidad'); }

    // Scopes
    public function scopeVigentes($query) { return $query->where('estado', 'vigente'); }
    public function scopePorVencer($query, $dias = 30) { return $query->whereBetween('fecha_vencimiento', [now(), now()->addDays($dias)]); }
    public function scopeVencidos($query) { return $query->where('fecha_vencimiento', '<', now()); }
    public function scopePorTipo($query, $tipo) { return $query->where('tipo_documento', $tipo); }

    // Accessors
    public function getDiasParaVencimientoAttribute() { return $this->fecha_vencimiento->diffInDays(now(), false); }
    public function getEstadoCalculadoAttribute() { $dias = $this->dias_para_vencimiento; return $dias < 0 ? 'vencido' : ($dias <= 30 ? 'por_vencer' : 'vigente'); }
    public function getEstadoBadgeAttribute() { return ['vigente' => 'success', 'por_vencer' => 'warning', 'vencido' => 'danger'][$this->estado_calculado] ?? 'secondary'; }
    public function getTipoDocumentoFormateadoAttribute() { return ['soat' => 'SOAT', 'revision_tecnica' => 'Revisión Técnica', 'tarjeta_propiedad' => 'Tarjeta de Propiedad', 'permiso_circulacion' => 'Permiso de Circulación'][$this->tipo_documento] ?? $this->tipo_documento; }

    protected static function boot() { parent::boot(); static::saving(fn($doc) => $doc->estado = $doc->estado_calculado); }
}