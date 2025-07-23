<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use App\Models\Conductor;
use App\Models\AsignacionVehicular;
use App\Http\Requests\VehiculoRequest;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index(Request $request)
    {
        $vehiculos = Vehiculo::with(['asignacionActiva.conductor', 'documentos'])
            ->when($request->buscar, fn($q) => $q->where('placa', 'like', "%{$request->buscar}%")->orWhere('marca', 'like', "%{$request->buscar}%"))
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->when($request->tipo, fn($q) => $q->where('tipo_vehiculo', $request->tipo))
            ->paginate(15);

        return view('vehiculos.index', compact('vehiculos'));
    }

    public function create()
    {
        return view('vehiculos.create');
    }

    public function store(VehiculoRequest $request)
    {
        $vehiculo = Vehiculo::create($request->validated() + ['codigo_qr' => 'QR-' . strtoupper($request->placa)]);
        return redirect()->route('vehiculos.index')->with('success', "Vehículo {$vehiculo->placa} registrado exitosamente.");
    }

    public function show(Vehiculo $vehiculo)
    {
        $vehiculo->load(['documentos', 'asignaciones.conductor', 'movimientos.destino', 'ordenesMantenimiento.taller']);
        return view('vehiculos.show', compact('vehiculo'));
    }

    public function edit(Vehiculo $vehiculo)
    {
        return view('vehiculos.edit', compact('vehiculo'));
    }

    public function update(VehiculoRequest $request, Vehiculo $vehiculo)
    {
        $vehiculo->update($request->validated());
        return redirect()->route('vehiculos.show', $vehiculo)->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        if ($vehiculo->movimientoActivo) return back()->with('error', 'No se puede eliminar un vehículo en movimiento.');
        
        $vehiculo->delete();
        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado correctamente.');
    }

    public function historial(Vehiculo $vehiculo)
    {
        $movimientos = $vehiculo->movimientos()->with(['conductor', 'destino'])->latest()->paginate(10);
        $mantenimientos = $vehiculo->ordenesMantenimiento()->with('taller')->latest()->paginate(10);
        return view('vehiculos.historial', compact('vehiculo', 'movimientos', 'mantenimientos'));
    }

    public function documentos(Vehiculo $vehiculo)
    {
        $documentos = $vehiculo->documentos()->latest()->get();
        return view('vehiculos.documentos.index', compact('vehiculo', 'documentos'));
    }

    public function asignarConductor(Request $request, Vehiculo $vehiculo)
    {
        $request->validate(['conductor_id' => 'required|exists:conductores,id']);
        
        // Finalizar asignación actual si existe
        $vehiculo->asignacionActiva?->update(['estado' => 'finalizada', 'fecha_desasignacion' => now()]);
        
        // Crear nueva asignación
        AsignacionVehicular::create([
            'vehiculo_id' => $vehiculo->id,
            'conductor_id' => $request->conductor_id,
            'fecha_asignacion' => now(),
            'estado' => 'activa'
        ]);

        return back()->with('success', 'Conductor asignado correctamente.');
    }

    public function disponibles(Request $request)
    {
        return Vehiculo::disponibles()
            ->select('id', 'placa', 'marca', 'modelo')
            ->when($request->q, fn($q) => $q->where('placa', 'like', "%{$request->q}%"))
            ->limit(10)
            ->get();
    }
}