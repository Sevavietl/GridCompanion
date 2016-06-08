<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class NumberFilter extends TyppedFilter
{
    const EQUALS = 1;
    const LESS_THAN = 2;
    const GREATER_THAN = 3;

    protected $signsTable = [
        self::EQUALS => '=',
        self::LESS_THAN => '<=',
        self::GREATER_THAN => '>='
    ];

    protected $allowedTypes = [1, 2, 3];

    protected function validateType()
    {
        if (!in_array($this->type, $this->allowedTypes)) {
            throw new DomainException('Type ' . $this->type . ' is not allowed for filtering.');
        }
    }

    protected function validateFilter()
    {
        if (!is_numeric($this->filter)) {
            throw new DomainException('Filter value ' . $this->filter . ' is not allowed. Filter value must be numeric.');
        }
    }

    protected function getCondition()
    {
        return $this->getColumnForQuery() . ' ' . $this->signsTable[$this->type] . ' ' . $this->filter;
    }
}
