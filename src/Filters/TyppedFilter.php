<?php

namespace Sevavietl\GridCompanion\Filters;

use InvalidArgumentException;

abstract class TyppedFilter extends Filter
{
    protected $type;
    protected $filter;

    public function __construct(array $columnFilter, array $hash)
    {
        parent::__construct($columnFilter, $hash);

        $this->setTypeAndFilter();
        $this->validateType();
        $this->validateFilter();
    }

    protected function setTypeAndFilter()
    {
        $value = $this->columnFilter[$this->columnId];

        if (!isset($value['type'])) {
            throw new InvalidArgumentException('Filter type not specified.');
        }
        $this->type = $value['type'];

        if (!isset($value['filter'])) {
            throw new InvalidArgumentException('Filter not specified.');
        }
        $this->filter = $value['filter'];
    }

    abstract protected function validateType();

    abstract protected function validateFilter();

    public function toArray()
    {
        return [
            'columnId' => $this->columnId,
            'type' => $this->type,
            'filter' => $this->filter,
            'condition' => $this->getCondition()
        ];
    }

    abstract protected function getCondition();
}
