<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AlertBadge extends Component
{
    public $type;
    public $message;
    public $dismissible;
    public $icon;
    public $autoHide;

    /**
     * Create a new component instance.
     *
     * @param string $type Tipo de alerta (success, error, warning, info)
     * @param string $message Mensaje a mostrar
     * @param bool $dismissible Si la alerta se puede cerrar
     * @param string|null $icon Icono personalizado
     * @param bool $autoHide Si la alerta se oculta automáticamente
     */
    public function __construct(
        $type = 'info',
        $message = '',
        $dismissible = true,
        $icon = null,
        $autoHide = true
    ) {
        $this->type = $type;
        $this->message = $message;
        $this->dismissible = $dismissible;
        $this->icon = $icon ?? $this->getDefaultIcon($type);
        $this->autoHide = $autoHide;
    }

    /**
     * Obtener el icono por defecto según el tipo de alerta
     */
    private function getDefaultIcon($type)
    {
        $icons = [
            'success' => 'fa-check-circle',
            'error' => 'fa-exclamation-circle',
            'warning' => 'fa-exclamation-triangle',
            'info' => 'fa-info-circle'
        ];

        return $icons[$type] ?? 'fa-info-circle';
    }

    /**
     * Obtener la clase Bootstrap correspondiente
     */
    public function getBootstrapClass()
    {
        $classes = [
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info'
        ];

        return $classes[$this->type] ?? 'alert-info';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.alert-badge');
    }
}