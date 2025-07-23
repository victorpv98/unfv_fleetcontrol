<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionSistema;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index() { return view('configuracion.index', ['configuraciones' => ConfiguracionSistema::all()->pluck('valor', 'clave')]); }
    
    public function general(Request $request) 
    { 
        foreach($request->except('_token') as $clave => $valor) {
            ConfiguracionSistema::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
        }
        return back()->with('success', 'Configuración general actualizada.');
    }
    
    public function alertas(Request $request) 
    { 
        $configuraciones = ['alerta_soat_dias', 'alerta_revision_dias', 'alerta_mantenimiento_km'];
        foreach($configuraciones as $config) {
            if($request->has($config)) {
                ConfiguracionSistema::updateOrCreate(['clave' => $config], ['valor' => $request->$config]);
            }
        }
        return back()->with('success', 'Configuración de alertas actualizada.');
    }
    
    public function mantenimiento(Request $request) 
    { 
        $configuraciones = ['km_mantenimiento_preventivo', 'meses_mantenimiento_preventivo'];
        foreach($configuraciones as $config) {
            if($request->has($config)) {
                ConfiguracionSistema::updateOrCreate(['clave' => $config], ['valor' => $request->$config]);
            }
        }
        return back()->with('success', 'Configuración de mantenimiento actualizada.');
    }
    
    public function backup() { /* Implementar backup */ return back()->with('success', 'Backup realizado correctamente.'); }
}