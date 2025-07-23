<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardCard extends Component
{
    public $title;
    public $value;
    public $subtitle;
    public $icon;
    public $color;
    public $route;
    public $trend;
    public $trendValue;
    public $trendColor;

    /**
     * Create a new component instance.
     *
     * @param string $title Título de la tarjeta
     * @param string|int $value Valor principal a mostrar
     * @param string|null $subtitle Subtítulo o información adicional
     * @param string $icon Icono de FontAwesome (ej: 'fa-car')
     * @param string $color Color del tema (primary, info, success, warning, danger)
     * @param string|null $route Ruta para el enlace (opcional)
     * @param string|null $trend Tipo de tendencia (up, down, neutral)
     * @param string|null $trendValue Valor de la tendencia (ej: '+5%')
     * @param string|null $trendColor Color de la tendencia (success, danger, muted)
     */
    public function __construct(
        $title,
        $value,
        $subtitle = null,
        $icon = 'fa-chart-bar',
        $color = 'primary',
        $route = null,
        $trend = null,
        $trendValue = null,
        $trendColor = 'muted'
    ) {
        $this->title = $title;
        $this->value = $value;
        $this->subtitle = $subtitle;
        $this->icon = $icon;
        $this->color = $color;
        $this->route = $route;
        $this->trend = $trend;
        $this->trendValue = $trendValue;
        $this->trendColor = $trendColor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.dashboard-card');
    }
}