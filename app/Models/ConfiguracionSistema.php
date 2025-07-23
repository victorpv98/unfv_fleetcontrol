<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionSistema extends Model
{
    protected $table = 'configuraciones_sistema';
    protected $fillable = ['clave', 'valor', 'descripcion', 'tipo_dato', 'categoria', 'updated_by'];

    // Relaciones
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }

    // Scopes
    public function scopePorCategoria($query, $categoria) { return $query->where('categoria', $categoria); }
    public function scopePorClave($query, $clave) { return $query->where('clave', $clave); }

    // Accessors
    public function getValorFormateadoAttribute() { switch ($this->tipo_dato) { case 'boolean': return $this->valor ? 'Sí' : 'No'; case 'date': return \Carbon\Carbon::parse($this->valor)->format('d/m/Y'); case 'integer': return number_format($this->valor); case 'decimal': return number_format($this->valor, 2); default: return $this->valor; } }

    // Métodos estáticos para configuraciones comunes
    public static function get($clave, $default = null) { return static::where('clave', $clave)->value('valor') ?? $default; }
    public static function set($clave, $valor, $descripcion = null, $categoria = 'general') { return static::updateOrCreate(['clave' => $clave], ['valor' => $valor, 'descripcion' => $descripcion, 'categoria' => $categoria, 'updated_by' => auth()->id()]); }
    public static function getDiasAlertaSOAT() { return (int)static::get('dias_alerta_soat', 30); }
    public static function getDiasAlertaRevisionTecnica() { return (int)static::get('dias_alerta_revision_tecnica', 15); }
    public static function getKmMantenimientoPreventivo() { return (int)static::get('km_mantenimiento_preventivo', 5000); }
}
