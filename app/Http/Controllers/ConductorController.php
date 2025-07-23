<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use App\Http\Requests\ConductorRequest;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function index(Request $request)
    {
        $conductores = Conductor::with(['asignacionActiva.vehiculo'])
            ->when($request->buscar, fn($q) => $q->where('nombres', 'like', "%{$request->buscar}%")->orWhere('apellidos', 'like', "%{$request->buscar}%")->orWhere('dni', 'like', "%{$request->buscar}%"))
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->when($request->categoria, fn($q) => $q->where('licencia_categoria', $request->categoria))
            ->paginate(15);

        return view('conductores.index', compact('conductores'));
    }

    public function create()
    {
        return view('conductores.create');
    }

    public function store(ConductorRequest $request)
    {
        $conductor = Conductor::create($request->validated());
        return redirect()->route('conductores.index')->with('success', "Conductor {$conductor->nombre_completo} registrado exitosamente.");
    }

    public function show(Conductor $conductor)
    {
        $conductor->load(['asignaciones.vehiculo', 'movimientos.vehiculo', 'movimientos.destino']);
        return view('conductores.show', compact('conductor'));
    }

    public function edit(Conductor $conductor)
    {
        return view('conductores.edit', compact('conductor'));
    }

    public function update(ConductorRequest $request, Conductor $conductor)
    {
        $conductor->update($request->validated());
        return redirect()->route('conductores.show', $conductor)->with('success', 'Conductor actualizado correctamente.');
    }

    public function destroy(Conductor $conductor)
    {
        if ($conductor->movimientoActivo) return back()->with('error', 'No se puede eliminar un conductor en movimiento.');
        
        $conductor->delete();
        return redirect()->route('conductores.index')->with('success', 'Conductor eliminado correctamente.');
    }

    public function historial(Conductor $conductor)
    {
        $movimientos = $conductor->movimientos()->with(['vehiculo', 'destino'])->latest()->paginate(15);
        return view('conductores.historial', compact('conductor', 'movimientos'));
    }

    public function toggleEstado(Conductor $conductor)
    {
        $conductor->update(['estado' => $conductor->estado === 'activo' ? 'inactivo' : 'activo']);
        return back()->with('success', "Estado del conductor cambiado a {$conductor->estado}.");
    }

    public function disponibles(Request $request)
    {
        return Conductor::disponibles()
            ->select('id', 'nombres', 'apellidos', 'licencia_categoria')
            ->when($request->q, fn($q) => $q->where('nombres', 'like', "%{$request->q}%")->orWhere('apellidos', 'like', "%{$request->q}%"))
            ->limit(10)
            ->get();
    }
}