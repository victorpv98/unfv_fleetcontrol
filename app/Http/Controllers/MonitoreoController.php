<?php

namespace App\Http\Controllers;

class MonitoreoController extends Controller
{
    public function tiempo_real() { return view('monitoreo.tiempo-real', ['movimientos' => \App\Models\MovimientoVehicular::enCurso()->with(['vehiculo', 'conductor'])->get()]); }
    public function mapa() { return view('monitoreo.mapa'); }
}
