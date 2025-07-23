<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $fillable = ['tabla', 'registro_id', 'accion', 'datos_anteriores', 'datos_nuevos', 'usuario_id', 'ip_address', 'user_agent'];
    protected $casts = ['datos_anteriores' => 'array', 'datos_nuevos' => 'array', 'ip_address' => 'string'];

    // Relaciones
    public function usuario() { return $this->belongsTo(User::class); }

    // Scopes
    public function scopePorTabla($query, $tabla) { return $query->where('tabla', $tabla); }
    public function scopePorUsuario($query, $usuarioId) { return $query->where('usuario_id', $usuarioId); }
    public function scopePorAccion($query, $accion) { return $query->where('accion', $accion); }
    public function scopeEntreFechas($query, $inicio, $fin) { return $query->whereBetween('created_at', [$inicio, $fin]); }
    public function scopeRecientes($query, $dias = 7) { return $query->where('created_at', '>=', now()->subDays($dias)); }

    // Accessors
    public function getAccionBadgeAttribute() { return ['INSERT' => 'success', 'UPDATE' => 'warning', 'DELETE' => 'danger'][$this->accion] ?? 'secondary'; }
    public function getTablaFormateadaAttribute() { return ucwords(str_replace('_', ' ', $this->tabla)); }
    public function getCambiosAttribute() { if ($this->accion === 'INSERT') return 'Registro creado'; if ($this->accion === 'DELETE') return 'Registro eliminado'; if ($this->accion === 'UPDATE' && $this->datos_anteriores && $this->datos_nuevos) { $cambios = []; foreach ($this->datos_nuevos as $campo => $valor) { if (isset($this->datos_anteriores[$campo]) && $this->datos_anteriores[$campo] != $valor) { $cambios[] = "{$campo}: '{$this->datos_anteriores[$campo]}' → '{$valor}'"; } } return implode(', ', $cambios); } return 'Sin cambios detectados'; }

    // Boot method para limpiar registros antiguos
    protected static function boot() { parent::boot(); static::created(function () { static::where('created_at', '<', now()->subMonths(6))->delete(); }); }
}
