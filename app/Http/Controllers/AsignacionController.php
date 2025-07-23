<?php

namespace App\Http\Controllers;
use App\Models\AsignacionVehicular;

class AsignacionController extends Controller
{
    public function index() { return view('asignaciones.index', ['asignaciones' => AsignacionVehicular::with(['vehiculo', 'conductor'])->latest()->paginate(15)]); }
    public function store(Request $request) { $request->validate(['vehiculo_id' => 'required', 'conductor_id' => 'required']); AsignacionVehicular::create($request->all() + ['fecha_asignacion' => now(), 'estado' => 'activa']); return back()->with('success', 'Asignación creada.'); }
    public function destroy(AsignacionVehicular $asignacion) { $asignacion->update(['estado' => 'finalizada', 'fecha_desasignacion' => now()]); return back()->with('success', 'Asignación finalizada.'); }
}