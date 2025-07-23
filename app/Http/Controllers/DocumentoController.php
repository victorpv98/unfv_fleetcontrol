<?php

namespace App\Http\Controllers;

use App\Models\DocumentoVehicular;
use App\Models\Vehiculo;
use App\Http\Requests\DocumentoRequest;

class DocumentoController extends Controller
{
    public function index() { return view('documentos.index', ['documentos' => DocumentoVehicular::with('vehiculo')->latest()->paginate(15)]); }
    public function vencimientos() { return view('documentos.vencimientos', ['documentos' => DocumentoVehicular::porVencer(30)->with('vehiculo')->get()]); }
    public function store(DocumentoRequest $request, Vehiculo $vehiculo) { $vehiculo->documentos()->create($request->validated()); return back()->with('success', 'Documento registrado.'); }
    public function update(DocumentoRequest $request, DocumentoVehicular $documento) { $documento->update($request->validated()); return back()->with('success', 'Documento actualizado.'); }
    public function destroy(DocumentoVehicular $documento) { $documento->delete(); return back()->with('success', 'Documento eliminado.'); }
    public function validarPlaca(Request $request) { return response()->json(['existe' => Vehiculo::where('placa', $request->placa)->exists()]); }
}