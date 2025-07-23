<?php

namespace App\Http\Controllers;

use App\Models\OrdenMantenimiento;
use App\Models\Cotizacion;
use App\Models\FacturaMantenimiento;
use App\Models\Vehiculo;
use App\Models\Taller;
use App\Http\Requests\MantenimientoRequest;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function index(Request $request)
    {
        $ordenes = OrdenMantenimiento::with(['vehiculo', 'taller', 'solicitadoPor'])
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->when($request->tipo, fn($q) => $q->where('tipo_mantenimiento', $request->tipo))
            ->when($request->vehiculo_id, fn($q) => $q->where('vehiculo_id', $request->vehiculo_id))
            ->latest('fecha_solicitud')
            ->paginate(15);

        $vehiculos = Vehiculo::activos()->select('id', 'placa')->get();
        return view('mantenimiento.index', compact('ordenes', 'vehiculos'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::activos()->get();
        $talleres = Taller::activos()->get();
        return view('mantenimiento.create', compact('vehiculos', 'talleres'));
    }

    public function store(MantenimientoRequest $request)
    {
        $orden = OrdenMantenimiento::create($request->validated() + [
            'solicitado_por' => auth()->id(),
            'fecha_solicitud' => now(),
            'estado' => 'solicitada'
        ]);

        return redirect()->route('mantenimiento.show', $orden)->with('success', 'Orden de mantenimiento creada correctamente.');
    }

    public function show(OrdenMantenimiento $mantenimiento)
    {
        $mantenimiento->load(['vehiculo', 'taller', 'solicitadoPor', 'aprobadoPor', 'cotizaciones', 'facturas']);
        return view('mantenimiento.show', compact('mantenimiento'));
    }

    public function edit(OrdenMantenimiento $mantenimiento)
    {
        if (!in_array($mantenimiento->estado, ['solicitada', 'cotizando'])) {
            return back()->with('error', 'Solo se pueden editar órdenes en estado solicitada o cotizando.');
        }
        
        $vehiculos = Vehiculo::activos()->get();
        $talleres = Taller::activos()->get();
        return view('mantenimiento.edit', compact('mantenimiento', 'vehiculos', 'talleres'));
    }

    public function update(MantenimientoRequest $request, OrdenMantenimiento $mantenimiento)
    {
        $mantenimiento->update($request->validated());
        return redirect()->route('mantenimiento.show', $mantenimiento)->with('success', 'Orden actualizada correctamente.');
    }

    public function destroy(OrdenMantenimiento $mantenimiento)
    {
        if ($mantenimiento->estado === 'en_proceso') {
            return back()->with('error', 'No se puede eliminar una orden en proceso.');
        }
        
        $mantenimiento->delete();
        return redirect()->route('mantenimiento.index')->with('success', 'Orden eliminada correctamente.');
    }

    public function ordenes(Request $request)
    {
        $ordenes = OrdenMantenimiento::with(['vehiculo', 'taller'])
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->latest()
            ->paginate(20);

        return view('mantenimiento.ordenes.index', compact('ordenes'));
    }

    public function cotizaciones(Request $request)
    {
        $cotizaciones = Cotizacion::with(['ordenMantenimiento.vehiculo', 'taller'])
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->latest()
            ->paginate(15);

        return view('mantenimiento.cotizaciones.index', compact('cotizaciones'));
    }

    public function facturas(Request $request)
    {
        $facturas = FacturaMantenimiento::with(['ordenMantenimiento.vehiculo', 'taller'])
            ->when($request->mes, fn($q) => $q->whereMonth('fecha_factura', $request->mes))
            ->when($request->año, fn($q) => $q->whereYear('fecha_factura', $request->año))
            ->latest()
            ->paginate(15);

        return view('mantenimiento.facturas.index', compact('facturas'));
    }

    public function aprobar(Request $request, OrdenMantenimiento $orden)
    {
        $request->validate(['observaciones_aprobacion' => 'nullable|string|max:500']);
        
        $orden->update([
            'estado' => 'aprobada',
            'aprobado_por' => auth()->id(),
            'observaciones' => $request->observaciones_aprobacion
        ]);

        return back()->with('success', 'Orden de mantenimiento aprobada correctamente.');
    }

    public function finalizar(Request $request, OrdenMantenimiento $orden)
    {
        $request->validate([
            'fecha_finalizacion' => 'required|date',
            'costo_real' => 'required|numeric|min:0',
            'observaciones_finalizacion' => 'nullable|string|max:500'
        ]);

        $orden->update([
            'estado' => 'finalizada',
            'fecha_finalizacion' => $request->fecha_finalizacion,
            'costo_real' => $request->costo_real,
            'observaciones' => $request->observaciones_finalizacion
        ]);

        return back()->with('success', 'Orden de mantenimiento finalizada correctamente.');
    }
}