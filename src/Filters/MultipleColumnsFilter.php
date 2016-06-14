<?php

namespace Sevavietl\GridCompanion\Filters;

use Sevavietl\GridCompanion\FilterFactory;

class MultipleColumnsFilter extends Filter
{
    protected $columnIds;
    protected $filter;

    protected $hash;

    protected $filterFactory;

    protected $columnFilters = [];

    public function __construct(array $columnIds, array $filter, array $hash)
    {
        $this->columnIds = $columnIds;
        $this->filter = $filter;
        $this->hash = $hash;

        $this->filterFactory = new FilterFactory();

        $this->setColumnFilters();
    }

    protected function setColumnFilters()
    {
        array_walk($this->columnIds, function ($columnId) {
            $this->columnFilters[] = $this->filterFactory->build(
                $this->hash,
                $columnId,
                $this->filter
            );
        });
    }

    public function toArray()
    {
        return [
            'columnId' => $this->columnIds,
            'filter' => $this->filter,
            'condition' => $this->getCondition()
        ];
    }

    public function getCondition()
    {
        return '(' .
            implode(' OR ', array_map(function ($columnFilter) {
                return $columnFilter->toArray()['condition'];
            }, $this->columnFilters)) .
        ')';
    }
}
