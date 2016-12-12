<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class TextFilter extends TyppedFilter
{
    const CONTAINS    = 'contains';
    const EQUALS      = 'equals';
    const NOT_EQUALS  = 'notEquals';
    const STARTS_WITH = 'startsWith';
    const ENDS_WITH   = 'endsWith';

    protected $templatesTable = [
        self::CONTAINS    => 'LIKE \'%{{condition}}%\'',
        self::EQUALS      => '= \'{{condition}}\'',
        self::NOT_EQUALS  => '<> \'{{condition}}\'',
        self::STARTS_WITH => 'LIKE \'{{condition}}%\'',
        self::ENDS_WITH   => 'LIKE \'%{{condition}}\''
    ];

    protected function validateType()
    {
        if (!isset($this->templatesTable[$this->type])) {
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
