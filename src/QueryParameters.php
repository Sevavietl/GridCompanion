<?php

namespace Sevavietl\GridCompanion;

use Sevavietl\GridCompanion\RowsInterval;

use Sevavietl\GridCompanion\ColumnDefinitions;
use Sevavietl\GridCompanion\Column\Column;

use Sevavietl\GridCompanion\FilterFactory;

use Sevavietl\GridCompanion\Filters\MultipleColumnsFilter;

use SplQueue;

class QueryParameters
{
    protected $parameters = [];

    protected $definitions;

    protected $select = [];
    protected $from = [];
    protected $join = [];

    protected $startRow;
    protected $endRow;

    protected $filterFactory;

    protected $filters = [];
    protected $sort = [];

    public function __construct(ColumnDefinitions $definitions)
    {
        $this->definitions = $definitions;

        $this->filterFactory = new FilterFactory();

        $this->setFrom();
        $this->setSelectAndJoin($this->definitions->getDefinitions());
    }

    protected function setFrom()
    {
        $this->from = [
            'model' => $this->definitions->getModel(),
            'alias' => $this->definitions->getAlias()
        ];
    }

    protected function setSelectAndJoin(SplQueue $definitions)
    {
        foreach ($definitions as $definition) {
            if ($definition->isColumnGroup()) {
                $this->setSelectAndJoin($definition->getColumns());
                continue;
            }

            $this->addSelect($definition);

            if (
                $definition->getModel() !== $this->definitions->getModel()
                && $definition->getAlias() !== $this->definitions->getAlias()
            ) {
                $this->addJoin($definition);
            }
        }
    }

    protected function addJoin(Column $column)
    {
        $join = [
            'model' => $column->getModel(),
            'alias' => $column->getAlias()
        ];

        if (in_array($join, $this->join)) {
            return;
        }

        $this->join[] = $join;
    }

    protected function addSelect(Column $column)
    {
        $select = [
            'alias' => $column->getAlias(),
            'column' => $column->getColumnName(),
            'columnAlias' => $column->getColumnAlias()
        ];

        if (in_array($select, $this->select)) {
            return;
        }

        $this->select[] = $select;
    }

    public function setRowsInterval(RowsInterval $rowsInterval)
    {
        $this->startRow = $rowsInterval->getStartRow();
        $this->endRow = $rowsInterval->getEndRow();

        return $this;
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    public function setSort(array $sort)
    {
        $this->sort = $sort;

        return $this;
    }

    public function getSelect()
    {
        return $this->select;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getJoin()
    {
        return $this->join;
    }

    public function getQueryParameters()
    {
        $this->parameters = [
            'select' => $this->select,
            'from'   => $this->from,
            'join'   => $this->join,
        ];

        $this->decorateWithFilters();
        $this->decorateWithSortings();
        $this->decorateWithRowsIntervals();

        return $this->parameters;
    }

    protected function decorateWithFilters()
    {

        $hash = $this->definitions->getHash();

        if (empty($hash)) {
            $this->parameters['filters'] = $this->filters;
            return;
        }

        $columnIds = array_keys($this->filters);

        $this->parameters['filters'] = array_combine(
            $columnIds,
            array_map(
                function ($columnId, $params) use ($hash) {
                    $filter = $this->filterFactory->build(
                        $hash,
                        $columnId,
                        $params
                    );

                    if ($filter instanceof MultipleColumnsFilter) {
                        return ['params' => $filter->toArray()];
                    }

                    return array_merge(
                        $hash[$columnId],
                        ['params' => $filter->toArray()]
                    );
                },
                $columnIds,
                array_values($this->filters)
            )
        );
    }

    protected function decorateWithSortings()
    {
        $hash = $this->definitions->getHash();

        if (empty($hash)) {
            $this->parameters['sort'] = $this->sort;
            return;
        }

        $this->parameters['sort'] = array_map(
            function ($item) use ($hash) {
                return array_merge(
                    $hash[$item['colId']],
                    $item
                );
            },
            $this->sort
        );
    }

    protected function decorateWithRowsIntervals()
    {
        if (!is_null($this->startRow) && !is_null($this->endRow)) {
            $this->parameters['startRow'] = $this->startRow;
            $this->parameters['endRow'] = $this->endRow;
        }
    }
}
