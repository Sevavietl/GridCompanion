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

    protected $signsTable = [
        self::EQUALS                => '=',
        self::NOT_EQUAL             => '<>',
        self::LESS_THAN             => '<',
        self::LESS_THAN_OR_EQUAL    => '<=',
        self::GREATER_THAN          => '>',
        self::GREATER_THAN_OR_EQUAL => '>=',
    ];

    protected function validateType()
    {
        if (!isset($this->signsTable[$this->type])) {
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
