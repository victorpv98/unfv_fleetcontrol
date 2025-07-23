<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use App\Http\Requests\DestinoRequest;

class DestinoController extends Controller
{
    public function index() { return view('destinos.index', ['destinos' => Destino::paginate(15)]); }
    public function create() { return view('destinos.create'); }
    public function store(DestinoRequest $request) { Destino::create($request->validated()); return redirect()->route('destinos.index')->with('success', 'Destino creado correctamente.'); }
    public function edit(Destino $destino) { return view('destinos.edit', compact('destino')); }
    public function update(DestinoRequest $request, Destino $destino) { $destino->update($request->validated()); return redirect()->route('destinos.index')->with('success', 'Destino actualizado.'); }
    public function destroy(Destino $destino) { $destino->delete(); return back()->with('success', 'Destino eliminado.'); }
    public function toggleEstado(Destino $destino) { $destino->update(['activo' => !$destino->activo]); return back()->with('success', 'Estado actualizado.'); }
}