<?php

namespace Sevavietl\GridCompanion;

use Sevavietl\GridCompanion\Filters\NumberFilter;
use Sevavietl\GridCompanion\Filters\TextFilter;
use Sevavietl\GridCompanion\Filters\SetFilter;
use Sevavietl\GridCompanion\Filters\SetEmptyFilter;
use Sevavietl\GridCompanion\Filters\DateRangeFilter;
use Sevavietl\GridCompanion\Filters\DateTimeFilter;
use Sevavietl\GridCompanion\Filters\MultipleColumnsFilter;

use DomainException;

class FilterFactory
{
    public function build(array $hash, $columnId, $params)
    {
        $columnIds = json_decode($columnId);

        if (!is_null($columnIds) && is_array($columnIds)) {
            return new MultipleColumnsFilter($columnIds, $params, $hash);
        }

        $hash = $hash[$columnId];
        $type = $hash['filterType'];

        switch ($type) {
            case 'number':
                return new NumberFilter([$columnId => $params], $hash);
            case 'text':
                return new TextFilter([$columnId => $params], $hash);
            case 'set':
                return new SetFilter([$columnId => $params], $hash);

            default:
                if (class_exists($type)) {
                    return new $type([$columnId => $params], $hash);
                }
        }

        throw new DomainException("There is no filter of type '$type'.");
    }
}
