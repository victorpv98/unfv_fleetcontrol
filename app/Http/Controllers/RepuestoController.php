<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use App\Http\Requests\RepuestoRequest;
use Illuminate\Http\Request;

class RepuestoController extends Controller
{
    public function index(Request $request) 
    { 
        $repuestos = Repuesto::when($request->categoria, fn($q) => $q->where('categoria', $request->categoria))
            ->when($request->stock_bajo, fn($q) => $q->stockBajo())
            ->paginate(15);
        return view('repuestos.index', compact('repuestos'));
    }
    
    public function create() { return view('repuestos.create'); }
    public function store(RepuestoRequest $request) { Repuesto::create($request->validated()); return redirect()->route('repuestos.index')->with('success', 'Repuesto registrado.'); }
    public function show(Repuesto $repuesto) { return view('repuestos.show', compact('repuesto')); }
    public function edit(Repuesto $repuesto) { return view('repuestos.edit', compact('repuesto')); }
    public function update(RepuestoRequest $request, Repuesto $repuesto) { $repuesto->update($request->validated()); return redirect()->route('repuestos.show', $repuesto)->with('success', 'Repuesto actualizado.'); }
    public function destroy(Repuesto $repuesto) { $repuesto->delete(); return redirect()->route('repuestos.index')->with('success', 'Repuesto eliminado.'); }
    public function stockBajo() { return view('repuestos.stock-bajo', ['repuestos' => Repuesto::stockBajo()->get()]); }
    
    public function ajustarStock(Request $request, Repuesto $repuesto)
    {
        $request->validate(['cantidad' => 'required|integer', 'motivo' => 'required|string']);
        $repuesto->ajustarStock($request->cantidad, $request->motivo);
        return back()->with('success', 'Stock ajustado correctamente.');
    }
}