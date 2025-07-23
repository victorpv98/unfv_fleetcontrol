@extends('layouts.app')
@section('title', 'Gestión de Repuestos')
@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary font-weight-bold"><i class="fas fa-cogs mr-2"></i>Gestión de Repuestos</h1>
                    <p class="text-muted mb-0">Inventario de repuestos y materiales</p>
                </div>
                <div>
                    <a href="{{ route('repuestos.stock-bajo') }}" class="btn btn-warning mr-2"><i class="fas fa-exclamation-triangle mr-2"></i>Stock Bajo</a>
                    <a href="{{ route('repuestos.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-2"></i>Nuevo Repuesto</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4"><input type="text" class="form-control" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por código o nombre..."></div>
                <div class="col-md-3">
                    <select name="categoria" class="form-control">
                        <option value="">Todas las categorías</option>
                        @foreach(App\Models\Repuesto::distinct()->pluck('categoria') as $categoria)
                            <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stock_bajo" class="form-control">
                        <option value="">Todo el stock</option>
                        <option value="1" {{ request('stock_bajo') ? 'selected' : '' }}>Solo stock bajo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-search mr-1"></i>Filtrar</button>
                    <a href="{{ route('repuestos.index') }}" class="btn btn-outline-secondary"><i class="fas fa-undo mr-1"></i>Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Repuesto</th>
                            <th class="border-0">Código</th>
                            <th class="border-0">Categoría</th>
                            <th class="border-0">Stock</th>
                            <th class="border-0">Precio</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repuestos as $repuesto)
                        <tr>
                            <td class="align-middle">
                                <div>
                                    <strong>{{ $repuesto->nombre }}</strong>
                                    <small class="d-block text-muted">{{ $repuesto->marca }} | {{ $repuesto->unidad_medida }}</small>
                                </div>
                            </td>
                            <td class="align-middle"><code>{{ $repuesto->codigo }}</code></td>
                            <td class="align-middle"><span class="badge badge-secondary">{{ $repuesto->categoria }}</span></td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <strong class="mr-2">{{ $repuesto->stock_actual }}</strong>
                                    <span class="badge badge-{{ $repuesto->estado_stock_badge }} badge-sm">{{ ucfirst($repuesto->estado_stock) }}</span>
                                </div>
                                <small class="text-muted">Mín: {{ $repuesto->stock_minimo }}</small>
                            </td>
                            <td class="align-middle">
                                @if($repuesto->precio_unitario)
                                    <strong>S/ {{ number_format($repuesto->precio_unitario, 2) }}</strong>
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-{{ $repuesto->activo ? 'success' : 'danger' }} badge-pill">
                                    {{ $repuesto->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group">
                                    <a href="{{ route('repuestos.show', $repuesto) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('repuestos.edit', $repuesto) }}" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#ajustarStock{{ $repuesto->id }}"><i class="fas fa-warehouse"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4"><i class="fas fa-cogs fa-3x mb-3 d-block text-muted"></i><h5>No hay repuestos registrados</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($repuestos->hasPages())
        <div class="card-footer bg-white">{{ $repuestos->links() }}</div>
        @endif
    </div>
</div>
@endsection