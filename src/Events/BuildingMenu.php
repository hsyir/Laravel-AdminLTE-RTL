<?php

namespace Hsy\LaravelAdminLte\Events;

use Hsy\LaravelAdminLte\Menu\Builder;

class BuildingMenu
{
    public $menu;

    public function __construct(Builder $menu)
    {
        $this->menu = $menu;
    }
}
