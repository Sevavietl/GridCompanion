<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class NumberFilter extends TyppedFilter
{
    const EQUALS = 'equals';
    const NOT_EQUAL = 'notEqual';
    const LESS_THAN = 'lessThan';
    const LESS_THAN_OR_EQUAL = 'lessThanOrEqual';
    const GREATER_THAN = 'greaterThan';
    const GREATER_THAN_OR_EQUAL = 'greaterThanOrEqual';

    protected $templatesTable = [
        self::EQUALS                => '{{column}} = {{condition}}',
        self::NOT_EQUAL             => '{{column}} <> {{condition}}',
        self::LESS_THAN             => '{{column}} < {{condition}}',
        self::LESS_THAN_OR_EQUAL    => '{{column}} <= {{condition}}',
        self::GREATER_THAN          => '{{column}} > {{condition}}',
        self::GREATER_THAN_OR_EQUAL => '{{column}} >= {{condition}}',
    ];

    protected function validateFilter()
    {
        if (!is_numeric($this->filter)) {
            throw new DomainException('Filter value ' . $this->filter . ' is not allowed. Filter value must be numeric.');
        }
    }

    protected function getTemplatesTable()
    {
        return $this->templatesTable;
    }
}
