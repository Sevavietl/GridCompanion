<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class NumberFilter extends TyppedFilter
{
    const EQUALS = 1;
    const NOT_EQUAL = 2;
    const LESS_THAN = 3;
    const LESS_THAN_OR_EQUAL = 4;
    const GREATER_THAN = 5;
    const GREATER_THAN_OR_EQUAL = 6;

    protected $signsTable = [
        self::EQUALS                => '=',
        self::NOT_EQUAL             => '<>',
        self::LESS_THAN             => '<',
        self::LESS_THAN_OR_EQUAL    => '<=',
        self::GREATER_THAN          => '>',
        self::GREATER_THAN_OR_EQUAL => '>=',
    ];

    protected $allowedTypes = [1, 2, 3, 4, 5, 6];

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
