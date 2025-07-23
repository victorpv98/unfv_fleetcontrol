<?php

namespace App\Http\Controllers;
use App\Models\Cotizacion;

class CotizacionController extends Controller
{
    public function store(Request $request) { Cotizacion::create($request->validate(['orden_mantenimiento_id' => 'required', 'taller_id' => 'required', 'costo_total' => 'required|numeric', 'descripcion_servicios' => 'required'])); return back()->with('success', 'Cotización registrada.'); }
    public function update(Request $request, Cotizacion $cotizacion) { $cotizacion->update(['estado' => $request->estado]); return back()->with('success', 'Cotización actualizada.'); }
}
