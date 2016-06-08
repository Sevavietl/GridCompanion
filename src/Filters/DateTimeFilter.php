<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class DateTimeFilter extends TyppedFilter
{
    const EQUALS = 1;
    const BEFORE = 2;
    const AFTER  = 3;

    protected $templatesTable = [
        self::EQUALS => 'LIKE \'{{condition}}%\'',
        self::BEFORE => '<= \'{{condition}}:00\'',
        self::AFTER  => '>= \'{{condition}}:00\''
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
        $datePattern = '/\d{4}\-\d{2}\-\d{2} \d{2}\:\d{2}/';

        if (!preg_match($datePattern, $this->filter)) {
            throw new DomainException('Incorrect date format.');
        }
    }

    protected function getCondition()
    {
        return $this->getColumnForQuery() . ' ' . str_replace(
            '{{condition}}',
            addslashes($this->filter),
            $this->templatesTable[$this->type]
        );
    }
}
