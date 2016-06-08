<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class SetEmptyFilter extends SetFilter
{
    protected $includeEmpty = false;

    protected function setFilter()
    {
        $this->filter = array_values(array_filter($this->columnFilter[$this->columnId], function ($item) {
            return $item !== '';
        }));

        $this->includeEmpty = count($this->filter) < count($this->columnFilter[$this->columnId]);
    }

    protected function getCondition()
    {
        $condition = '';

        if ($this->includeEmpty) {
            $condition .= $this->getColumnForQuery() . ' IS NULL';
        }

        if (!empty($this->filter)) {
            if ($this->includeEmpty) {
                $condition .= ' OR ';
            }

            $condition .= $this->getColumnForQuery() . ' IN (';
            $condition .= implode(', ', array_map(function ($item) {
                $item = addslashes((string) $item);
                return "'$item'";
            }, $this->filter));
            $condition .= ')';
        }

        return $condition;
    }
}
