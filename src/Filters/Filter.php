<?php

namespace Sevavietl\GridCompanion\Filters;

abstract class Filter
{
    protected $columnFilter;
    protected $hash;

    protected $columnId;

    public function __construct(array $columnFilter, array $hash)
    {
        $this->columnFilter = $columnFilter;
        $this->hash = $hash;

        $this->setColumnId();
    }

    protected function setColumnId()
    {
        $this->columnId = key($this->columnFilter);
    }

    abstract public function toArray();

    protected function getColumnForQuery()
    {
        return $this->hash['alias'] . '.' . $this->hash['column'];
    }
}
