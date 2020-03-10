<?php

namespace Hsy\LaravelAdminLte\Menu\Filters;

use Hsy\LaravelAdminLte\Menu\ActiveChecker;
use Hsy\LaravelAdminLte\Menu\Builder;

class ActiveFilter implements FilterInterface
{
    private $activeChecker;

    public function __construct(ActiveChecker $activeChecker)
    {
        $this->activeChecker = $activeChecker;
    }

    public function transform($item, Builder $builder)
    {
        if (! isset($item['header'])) {
            $item['active'] = $this->activeChecker->isActive($item);
        }

        return $item;
    }
}
