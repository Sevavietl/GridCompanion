<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class DateTimeFilter extends TyppedFilter
{
    const EQUALS = 'equals';
    const BEFORE = 'before';
    const AFTER  = 'after';

    protected $templatesTable = [
        self::EQUALS => '{{column}} LIKE \'{{condition}}%\'',
        self::BEFORE => '{{column}} <= \'{{condition}}:00\'',
        self::AFTER  => '{{column}} >= \'{{condition}}:00\''
    ];

    protected function validateType()
    {
        if (!isset($this->templatesTable[$this->type])) {
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
}
