<?php

namespace App\Http\Controllers;
use App\Models\InspeccionVehicular;

class InspeccionController extends Controller
{
    public function index() { return view('inspecciones.index', ['inspecciones' => InspeccionVehicular::with(['movimiento.vehiculo', 'inspector'])->latest()->paginate(15)]); }
    public function show(InspeccionVehicular $inspeccion) { return view('inspecciones.show', compact('inspeccion')); }
}
