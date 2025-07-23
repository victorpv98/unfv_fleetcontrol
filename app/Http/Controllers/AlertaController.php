<?php

namespace App\Http\Controllers;

use App\Models\Alerta;

class AlertaController extends Controller
{
    public function index() { return view('alertas.index', ['alertas' => Alerta::with('entidad')->latest()->paginate(15)]); }
    public function show(Alerta $alerta) { return view('alertas.show', compact('alerta')); }
    public function update(Alerta $alerta) { $alerta->update(['estado' => 'leida', 'leida_en' => now()]); return back()->with('success', 'Alerta marcada como leída.'); }
    public function destroy(Alerta $alerta) { $alerta->delete(); return back()->with('success', 'Alerta eliminada.'); }
    public function marcarLeida(Alerta $alerta) { $alerta->update(['estado' => 'leida']); return response()->json(['success' => true]); }
    public function marcarTodasLeidas() { Alerta::where('estado', 'pendiente')->update(['estado' => 'leida']); return back()->with('success', 'Todas las alertas marcadas como leídas.'); }
    public function count() { return response()->json(['count' => Alerta::where('estado', 'pendiente')->count()]); }
}
