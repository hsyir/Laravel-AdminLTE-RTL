<?php

namespace Hsy\LaravelAdminLte;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Hsy\LaravelAdminLte\Events\BuildingMenu;
use Hsy\LaravelAdminLte\Menu\Builder;

class AdminLte
{
    protected $menu;

    protected $filters;

    protected $events;

    protected $container;

    public function __construct(
        array $filters,
        Dispatcher $events,
        Container $container
    ) {
        $this->filters = $filters;
        $this->events = $events;
        $this->container = $container;
    }

    public function menu()
    {
        if (! $this->menu) {
            $this->menu = $this->buildMenu();
        }

        return $this->menu;
    }

    protected function buildMenu()
    {
        $builder = new Builder($this->buildFilters());

        if (method_exists($this->events, 'dispatch')) {
            $this->events->dispatch(new BuildingMenu($builder));
        } else {
            $this->events->fire(new BuildingMenu($builder));
        }

        return $builder->menu;
    }

    protected function buildFilters()
    {
        return array_map([$this->container, 'make'], $this->filters);
    }
}
