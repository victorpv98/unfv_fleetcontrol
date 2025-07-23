<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\MovimientoVehicular;
use App\Models\OrdenMantenimiento;

class ReporteController extends Controller
{
    public function index() { return view('reportes.index'); }
    public function flota() { return view('reportes.flota', ['vehiculos' => Vehiculo::with(['asignacionActiva.conductor', 'documentos'])->get()]); }
    public function movimientos() { return view('reportes.movimientos', ['movimientos' => MovimientoVehicular::with(['vehiculo', 'conductor', 'destino'])->latest()->limit(100)->get()]); }
    public function mantenimiento() { return view('reportes.mantenimiento', ['ordenes' => OrdenMantenimiento::with(['vehiculo', 'taller'])->latest()->limit(50)->get()]); }
    public function combustible() { return view('reportes.combustible', ['consumo' => MovimientoVehicular::selectRaw('vehiculo_id, SUM(combustible_abastecido) as total')->with('vehiculo')->groupBy('vehiculo_id')->get()]); }
    public function costos() { return view('reportes.costos', ['costos' => OrdenMantenimiento::selectRaw('MONTH(fecha_solicitud) as mes, SUM(costo_real) as total')->whereYear('fecha_solicitud', date('Y'))->groupBy('mes')->get()]); }
    public function documentos() { return view('reportes.documentos', ['vencidos' => \App\Models\DocumentoVehicular::vencidos()->with('vehiculo')->get(), 'porVencer' => \App\Models\DocumentoVehicular::porVencer(30)->with('vehiculo')->get()]); }
    public function exportarPDF() { /* Implementar exportación PDF */ }
    public function exportarExcel() { /* Implementar exportación Excel */ }
}
