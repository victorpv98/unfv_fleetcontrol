<div class="card border-0 shadow-sm">
    @if($searchable)
    <div class="card-header bg-white">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="card-title mb-0">
                    {{ $slot ?? 'Listado de Registros' }}
                </h6>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" 
                           class="form-control border-left-0" 
                           placeholder="Buscar..." 
                           id="searchInput"
                           onkeyup="filterTable()">
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 {{ $tableClass }}" id="dataTable">
                @if(!empty($headers))
                <thead class="bg-light">
                    <tr>
                        @foreach($headers as $key => $header)
                        <th class="border-0 {{ $sortable ? 'sortable' : '' }}" 
                            @if($sortable) 
                                style="cursor: pointer;" 
                                onclick="sortTable({{ $loop->index }})"
                                data-sort="none"
                            @endif>
                            {{ is_array($header) ? $header['label'] : $header }}
                            @if($sortable)
                                <i class="fas fa-sort text-muted ml-1 sort-icon"></i>
                            @endif
                        </th>
                        @endforeach
                        
                        @if($actions)
                        <th class="border-0 text-center" style="width: 120px;">Acciones</th>
                        @endif
                    </tr>
                </thead>
                @endif

                <tbody>
                    @forelse($data as $row)
                    <tr>
                        @foreach($headers as $key => $header)
                        <td class="align-middle">
                            @if(is_array($header) && isset($header['type']))
                                @switch($header['type'])
                                    @case('badge')
                                        @php
                                            $value = is_array($row) ? $row[$key] : $row->{$key};
                                            $badgeClass = $header['class'] ?? 'badge-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} badge-pill">
                                            {{ $value }}
                                        </span>
                                        @break
                                    
                                    @case('date')
                                        @php
                                            $value = is_array($row) ? $row[$key] : $row->{$key};
                                            $date = $value ? \Carbon\Carbon::parse($value) : null;
                                        @endphp
                                        {{ $date ? $date->format('d/m/Y') : '-' }}
                                        @break
                                    
                                    @case('datetime')
                                        @php
                                            $value = is_array($row) ? $row[$key] : $row->{$key};
                                            $date = $value ? \Carbon\Carbon::parse($value) : null;
                                        @endphp
                                        {{ $date ? $date->format('d/m/Y H:i') : '-' }}
                                        @break
                                    
                                    @case('currency')
                                        @php
                                            $value = is_array($row) ? $row[$key] : $row->{$key};
                                        @endphp
                                        S/ {{ number_format($value, 2) }}
                                        @break
                                    
                                    @case('boolean')
                                        @php
                                            $value = is_array($row) ? $row[$key] : $row->{$key};
                                        @endphp
                                        @if($value)
                                            <i class="fas fa-check text-success"></i>
                                        @else
                                            <i class="fas fa-times text-danger"></i>
                                        @endif
                                        @break
                                    
                                    @default
                                        {{ is_array($row) ? $row[$key] : $row->{$key} }}
                                @endswitch
                            @else
                                {{ is_array($row) ? $row[$key] : $row->{$key} }}
                            @endif
                        </td>
                        @endforeach
                        
                        @if($actions)
                        <td class="text-center align-middle">
                            <div class="btn-group" role="group">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        data-toggle="tooltip" 
                                        title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-warning" 
                                        data-toggle="tooltip" 
                                        title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        data-toggle="tooltip" 
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">{{ $emptyMessage }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($pagination && method_exists($data, 'links'))
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Mostrando {{ $data->firstItem() ?? 0 }} a {{ $data->lastItem() ?? 0 }} 
                de {{ $data->total() ?? 0 }} registros
            </div>
            <div>
                {{ $data->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.sortable:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.sort-icon {
    transition: all 0.3s;
}

.sortable[data-sort="asc"] .sort-icon:before {
    content: "\f0de"; /* fa-sort-up */
}

.sortable[data-sort="desc"] .sort-icon:before {
    content: "\f0dd"; /* fa-sort-down */
}

.table tbody tr:hover {
    background-color: rgba(0, 185, 241, 0.05);
}
</style>

<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('dataTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length - 1; j++) { // -1 para excluir columna de acciones
            if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}

function sortTable(columnIndex) {
    const table = document.getElementById('dataTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    const header = table.getElementsByTagName('th')[columnIndex];
    const currentSort = header.getAttribute('data-sort') || 'none';
    
    // Reset all other headers
    const headers = table.getElementsByTagName('th');
    for (let i = 0; i < headers.length; i++) {
        if (i !== columnIndex) {
            headers[i].setAttribute('data-sort', 'none');
        }
    }
    
    // Determine new sort direction
    let newSort;
    if (currentSort === 'none' || currentSort === 'desc') {
        newSort = 'asc';
    } else {
        newSort = 'desc';
    }
    
    header.setAttribute('data-sort', newSort);
    
    // Sort rows
    rows.sort((a, b) => {
        const aText = a.getElementsByTagName('td')[columnIndex].textContent.trim();
        const bText = b.getElementsByTagName('td')[columnIndex].textContent.trim();
        
        // Try to parse as numbers
        const aNum = parseFloat(aText.replace(/[^0-9.-]/g, ''));
        const bNum = parseFloat(bText.replace(/[^0-9.-]/g, ''));
        
        let comparison;
        if (!isNaN(aNum) && !isNaN(bNum)) {
            comparison = aNum - bNum;
        } else {
            comparison = aText.localeCompare(bText);
        }
        
        return newSort === 'asc' ? comparison : -comparison;
    });
    
    // Rebuild tbody
    rows.forEach(row => tbody.appendChild(row));
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>