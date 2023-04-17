<?php
declare(strict_types=1);

namespace LkInteractive\Back\LkpCarrousel\Grid;
use LkInteractive\Back\LkpCarrousel\Grid\CarrouselGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Search\Filters;

class CarrouselFilters extends Filters
{
    protected $filterId = CarrouselGridDefinitionFactory::GRID_ID;

    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_carrousel',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}