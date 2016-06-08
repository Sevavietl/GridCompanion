<?php

namespace Sevavietl\GridCompanion;

use Sevavietl\GridCompanion\Filters\NumberFilter;
use Sevavietl\GridCompanion\Filters\TextFilter;
use Sevavietl\GridCompanion\Filters\SetFilter;
use Sevavietl\GridCompanion\Filters\SetEmptyFilter;
use Sevavietl\GridCompanion\Filters\DateRangeFilter;
use Sevavietl\GridCompanion\Filters\DateTimeFilter;

use DomainException;

class FilterFactory
{
    public function build(array $hash, $columnId, $params)
    {
        $type = $hash['filterType'];

        switch ($type) {
            case 'number':
                return new NumberFilter([$columnId => $params], $hash);
                break;
            case 'text':
                return new TextFilter([$columnId => $params], $hash);
                break;
            case 'set':
            case 'SetFilter':
                return new SetFilter([$columnId => $params], $hash);
                break;
            case 'SetEmptyFilter':
                return new SetEmptyFilter([$columnId => $params], $hash);
                break;
            case 'DateRangeFilter':
                return new DateRangeFilter([$columnId => $params], $hash);
                break;
            case 'DateTimeFilter':
                return new DateTimeFilter([$columnId => $params], $hash);
                break;

            default:
                throw new DomainException("There is no filter of type '$type'.");
                break;
        }
    }
}
