<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request) 
    { 
        $auditorias = Auditoria::with('usuario')
            ->when($request->usuario, fn($q) => $q->where('usuario_id', $request->usuario))
            ->when($request->accion, fn($q) => $q->where('accion', $request->accion))
            ->when($request->fecha, fn($q) => $q->whereDate('created_at', $request->fecha))
            ->latest()
            ->paginate(20);
        return view('auditoria.index', compact('auditorias'));
    }
    
    public function show(Auditoria $auditoria) { return view('auditoria.detalle', compact('auditoria')); }
    public function limpiar() { Auditoria::where('created_at', '<', now()->subMonths(6))->delete(); return back()->with('success', 'Registros antiguos eliminados.'); }
}
