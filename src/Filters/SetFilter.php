<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class SetFilter extends Filter
{
    protected $filter;

    public function __construct(array $columnFilter, array $hash)
    {
        parent::__construct($columnFilter, $hash);

        $this->setFilter();
        $this->validateFilter();
    }

    protected function setFilter()
    {
        $this->filter = $this->columnFilter[$this->columnId];
    }

    protected function validateFilter()
    {
        array_walk($this->filter, function ($item) {
            if (!is_scalar($item)) {
                throw new DomainException('Filter elements of the SetFilter must be scalar.');
            }
        });
    }

    public function toArray()
    {
        return [
            'columnId' => $this->columnId,
            'filter' => $this->filter,
            'condition' => $this->getCondition()
        ];
    }

    protected function getCondition()
    {
        $condition = $this->getColumnForQuery() . ' IN (';
        $condition .= implode(', ', array_map(function ($item) {
            $item = addslashes((string) $item);
            return "'$item'";
        }, $this->filter));
        $condition .= ')';

        return $condition;
    }
}
