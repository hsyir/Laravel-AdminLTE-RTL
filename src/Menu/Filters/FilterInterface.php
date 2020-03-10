<?php

namespace Hsy\LaravelAdminLte\Menu\Filters;

use Hsy\LaravelAdminLte\Menu\Builder;

interface FilterInterface
{
    public function transform($item, Builder $builder);
}
