<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class TextFilter extends TyppedFilter
{
    const CONTAINS    = 1;
    const EQUALS      = 2;
    const NOT_EQUALS  = 3;
    const STARTS_WITH = 4;
    const ENDS_WITH   = 5;

    protected $templatesTable = [
        self::CONTAINS    => 'LIKE \'%{{condition}}%\'',
        self::EQUALS      => '= \'%{{condition}}%\'',
        self::NOT_EQUALS  => '<> \'%{{condition}}%\'',
        self::STARTS_WITH => 'LIKE \'{{condition}}%\'',
        self::ENDS_WITH   => 'LIKE \'%{{condition}}\''
    ];

    protected $allowedTypes = [1, 2, 3, 4, 5];

    protected function validateType()
    {
        if (!in_array($this->type, $this->allowedTypes)) {
            throw new DomainException('Type ' . $this->type . ' is not allowed for filtering.');
        }
    }

    protected function validateFilter()
    {

    }

    protected function getCondition()
    {
        return  $this->getColumnForQuery() . ' ' . str_replace(
            '{{condition}}',
            addslashes($this->filter),
            $this->templatesTable[$this->type]
        );
    }
}
