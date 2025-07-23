<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index() { return view('usuarios.index', ['usuarios' => User::paginate(15)]); }
    public function create() { return view('usuarios.create'); }
    public function store(UsuarioRequest $request) { User::create($request->validated() + ['password' => Hash::make($request->password)]); return redirect()->route('usuarios.index')->with('success', 'Usuario creado.'); }
    public function show(User $usuario) { return view('usuarios.show', compact('usuario')); }
    public function edit(User $usuario) { return view('usuarios.edit', compact('usuario')); }
    public function update(UsuarioRequest $request, User $usuario) { $usuario->update($request->validated() + ($request->password ? ['password' => Hash::make($request->password)] : [])); return redirect()->route('usuarios.show', $usuario)->with('success', 'Usuario actualizado.'); }
    public function destroy(User $usuario) { if($usuario->id === auth()->id()) return back()->with('error', 'No puedes eliminarte a ti mismo.'); $usuario->delete(); return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.'); }
    public function toggleEstado(User $usuario) { $usuario->update(['activo' => !$usuario->activo]); return back()->with('success', 'Estado actualizado.'); }
    public function resetPassword(User $usuario) { $usuario->update(['password' => Hash::make('123456')]); return back()->with('success', 'Contraseña restablecida a: 123456'); }
}