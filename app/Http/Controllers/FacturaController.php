<?php

namespace App\Http\Controllers;
use App\Models\FacturaMantenimiento;

class FacturaController extends Controller
{
    public function store(Request $request) { FacturaMantenimiento::create($request->validate(['orden_mantenimiento_id' => 'required', 'numero_factura' => 'required', 'monto_total' => 'required|numeric', 'fecha_factura' => 'required|date'])); return back()->with('success', 'Factura registrada.'); }
}
