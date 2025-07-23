<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTable extends Component
{
    public $headers;
    public $data;
    public $actions;
    public $searchable;
    public $sortable;
    public $pagination;
    public $emptyMessage;
    public $tableClass;

    /**
     * Create a new component instance.
     *
     * @param array $headers Cabeceras de la tabla
     * @param mixed $data Datos a mostrar (Collection o Array)
     * @param bool $actions Si mostrar columna de acciones
     * @param bool $searchable Si la tabla es buscable
     * @param bool $sortable Si las columnas son ordenables
     * @param bool $pagination Si mostrar paginación
     * @param string $emptyMessage Mensaje cuando no hay datos
     * @param string $tableClass Clases CSS adicionales para la tabla
     */
    public function __construct(
        $headers = [],
        $data = [],
        $actions = true,
        $searchable = true,
        $sortable = true,
        $pagination = true,
        $emptyMessage = 'No se encontraron registros',
        $tableClass = ''
    ) {
        $this->headers = $headers;
        $this->data = $data;
        $this->actions = $actions;
        $this->searchable = $searchable;
        $this->sortable = $sortable;
        $this->pagination = $pagination;
        $this->emptyMessage = $emptyMessage;
        $this->tableClass = $tableClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.data-table');
    }
}