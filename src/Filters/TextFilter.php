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
        self::CONTAINS    => '{{column}} LIKE \'%{{condition}}%\'',
        self::EQUALS      => '{{column}} = \'{{condition}}\'',
        self::NOT_EQUALS  => '{{column}} <> \'{{condition}}\'',
        self::STARTS_WITH => '{{column}} LIKE \'{{condition}}%\'',
        self::ENDS_WITH   => '{{column}} LIKE \'%{{condition}}\''
    ];

    protected function validateFilter()
    {

    }

    protected function getTemplatesTable()
    {
        return $this->templatesTable;
    }
}
