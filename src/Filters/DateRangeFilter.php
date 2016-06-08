<?php

namespace Sevavietl\GridCompanion\Filters;

use DomainException;

class DateRangeFilter extends Filter
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
        if (!isset($this->filter['from'])) {
            throw new DomainException('Filter must contain "from" option.');
        }

        if (!isset($this->filter['to'])) {
            throw new DomainException('Filter must contain "to" option.');
        }
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
        if ($this->filter['from'] === '' && $this->filter['to'] === '') {
            return '';
        }

        if ($this->filter['to'] === '') {
            return $this->getColumnForQuery() . ' >= \'' . addslashes($this->filter['from']) . '\'';
        }

        if ($this->filter['from'] === '') {
            return $this->getColumnForQuery() . ' <= \'' . addslashes($this->filter['to']) . '\'';
        }

        return $this->getColumnForQuery() . ' BETWEEN \'' . addslashes($this->filter['from']) . '\' AND \'' . addslashes($this->filter['to']) . '\'';
    }
}
