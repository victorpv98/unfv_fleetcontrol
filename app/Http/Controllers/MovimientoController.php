<?php

namespace App\Http\Controllers;

use App\Models\MovimientoVehicular;
use App\Models\InspeccionVehicular;
use App\Models\Vehiculo;
use App\Models\Conductor;
use App\Models\Destino;
use App\Http\Requests\MovimientoRequest;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $movimientos = MovimientoVehicular::with(['vehiculo', 'conductor', 'destino', 'autorizadoPor'])
            ->when($request->estado, fn($q) => $q->where('estado', $request->estado))
            ->when($request->fecha_inicio, fn($q) => $q->whereDate('fecha_hora_salida', '>=', $request->fecha_inicio))
            ->when($request->fecha_fin, fn($q) => $q->whereDate('fecha_hora_salida', '<=', $request->fecha_fin))
            ->when($request->vehiculo_id, fn($q) => $q->where('vehiculo_id', $request->vehiculo_id))
            ->latest('fecha_hora_salida')
            ->paginate(15);

        $vehiculos = Vehiculo::activos()->select('id', 'placa')->get();
        return view('movimientos.index', compact('movimientos', 'vehiculos'));
    }

    public function create()
    {
        $vehiculos = Vehiculo::disponibles()->get();
        $conductores = Conductor::disponibles()->get();
        $destinos = Destino::activos()->get();
        return view('movimientos.create', compact('vehiculos', 'conductores', 'destinos'));
    }

    public function store(MovimientoRequest $request)
    {
        $movimiento = MovimientoVehicular::create($request->validated() + [
            'autorizado_por' => auth()->id(),
            'estado' => 'en_curso'
        ]);

        return redirect()->route('movimientos.inspeccion-salida', $movimiento)
            ->with('success', 'Movimiento registrado. Complete la inspección de salida.');
    }

    public function show(MovimientoVehicular $movimiento)
    {
        $movimiento->load(['vehiculo', 'conductor', 'destino', 'inspecciones', 'autorizadoPor']);
        return view('movimientos.show', compact('movimiento'));
    }

    public function edit(MovimientoVehicular $movimiento)
    {
        if ($movimiento->estado !== 'en_curso') return back()->with('error', 'Solo se pueden editar movimientos en curso.');
        
        $destinos = Destino::activos()->get();
        return view('movimientos.edit', compact('movimiento', 'destinos'));
    }

    public function update(MovimientoRequest $request, MovimientoVehicular $movimiento)
    {
        $movimiento->update($request->validated());
        return redirect()->route('movimientos.show', $movimiento)->with('success', 'Movimiento actualizado correctamente.');
    }

    public function activos()
    {
        $movimientos = MovimientoVehicular::enCurso()->with(['vehiculo', 'conductor', 'destino'])->get();
        return view('movimientos.activos', compact('movimientos'));
    }

    public function finalizar(Request $request, MovimientoVehicular $movimiento)
    {
        $request->validate([
            'fecha_hora_entrada' => 'required|date|after:' . $movimiento->fecha_hora_salida,
            'kilometraje_entrada' => 'required|integer|min:' . $movimiento->kilometraje_salida,
            'combustible_final' => 'required|numeric|min:0',
            'observaciones_entrada' => 'nullable|string|max:500'
        ]);

        $movimiento->update($request->all() + [
            'kilometros_recorridos' => $request->kilometraje_entrada - $movimiento->kilometraje_salida,
            'estado' => 'finalizado'
        ]);

        return redirect()->route('movimientos.inspeccion-entrada', $movimiento)
            ->with('success', 'Movimiento finalizado. Complete la inspección de entrada.');
    }

    public function inspeccionSalida(MovimientoVehicular $movimiento)
    {
        $inspeccion = $movimiento->inspeccionSalida;
        return view('movimientos.inspecciones.salida', compact('movimiento', 'inspeccion'));
    }

    public function guardarInspeccionSalida(Request $request, MovimientoVehicular $movimiento)
    {
        $data = $request->validate([
            'nivel_combustible' => 'required|string',
            'estado_neumaticos' => 'required|string',
            'luces_funcionando' => 'required|boolean',
            'frenos_funcionando' => 'required|boolean',
            'nivel_aceite' => 'required|string',
            'limpieza_vehiculo' => 'required|string',
            'documentos_vehiculo' => 'required|boolean',
            'kit_emergencia' => 'required|boolean',
            'observaciones' => 'nullable|string|max:500'
        ]);

        InspeccionVehicular::updateOrCreate(
            ['movimiento_id' => $movimiento->id, 'tipo_inspeccion' => 'salida'],
            $data + ['inspector_id' => auth()->id()]
        );

        return redirect()->route('movimientos.index')->with('success', 'Inspección de salida registrada correctamente.');
    }

    public function inspeccionEntrada(MovimientoVehicular $movimiento)
    {
        $inspeccion = $movimiento->inspeccionEntrada;
        return view('movimientos.inspecciones.entrada', compact('movimiento', 'inspeccion'));
    }

    public function guardarInspeccionEntrada(Request $request, MovimientoVehicular $movimiento)
    {
        $data = $request->validate([
            'nivel_combustible' => 'required|string',
            'estado_neumaticos' => 'required|string',
            'luces_funcionando' => 'required|boolean',
            'frenos_funcionando' => 'required|boolean',
            'nivel_aceite' => 'required|string',
            'limpieza_vehiculo' => 'required|string',
            'documentos_vehiculo' => 'required|boolean',
            'kit_emergencia' => 'required|boolean',
            'observaciones' => 'nullable|string|max:500'
        ]);

        InspeccionVehicular::updateOrCreate(
            ['movimiento_id' => $movimiento->id, 'tipo_inspeccion' => 'entrada'],
            $data + ['inspector_id' => auth()->id()]
        );

        return redirect()->route('movimientos.show', $movimiento)->with('success', 'Inspección de entrada registrada correctamente.');
    }

    public function imprimirMA122(MovimientoVehicular $movimiento)
    {
        $movimiento->load(['vehiculo', 'conductor', 'destino', 'inspecciones']);
        return view('pdf.formulario-ma122', compact('movimiento'));
    }
}