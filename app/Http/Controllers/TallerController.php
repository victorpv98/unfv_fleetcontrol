<?php

namespace App\Http\Controllers;

use App\Models\Taller;
use App\Http\Requests\TallerRequest;

class TallerController extends Controller
{
    public function index() { return view('talleres.index', ['talleres' => Taller::withCount('ordenesMantenimiento')->paginate(15)]); }
    public function create() { return view('talleres.create'); }
    public function store(TallerRequest $request) { Taller::create($request->validated()); return redirect()->route('talleres.index')->with('success', 'Taller registrado correctamente.'); }
    public function show(Taller $taller) { $taller->load(['ordenesMantenimiento.vehiculo']); return view('talleres.show', compact('taller')); }
    public function edit(Taller $taller) { return view('talleres.edit', compact('taller')); }
    public function update(TallerRequest $request, Taller $taller) { $taller->update($request->validated()); return redirect()->route('talleres.show', $taller)->with('success', 'Taller actualizado.'); }
    public function destroy(Taller $taller) { $taller->delete(); return redirect()->route('talleres.index')->with('success', 'Taller eliminado.'); }
    public function toggleEstado(Taller $taller) { $taller->update(['activo' => !$taller->activo]); return back()->with('success', 'Estado actualizado.'); }
}