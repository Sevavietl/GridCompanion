<?php

namespace Sevavietl\GridCompanion\Filters;

use InvalidArgumentException;
use DomainException;

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

    protected function validateType()
    {
        if (!isset($this->templatesTable[$this->type])) {
            throw new DomainException('Type ' . $this->type . ' is not allowed for filtering.');
        }
    }

    abstract protected function validateFilter();

    public function toArray()
    {
        return [
            'columnId' => $this->columnId,
            'type' => $this->type,
            'filter' => $this->filter,

            'column' => $this->getColumnForQuery(),
            'conditionTemplate' => $this->getConditionTemplate(),
            'condition' => $this->getCondition()
        ];
    }

    protected function getCondition()
    {
        return  str_replace(
            ['{{column}}', '{{condition}}'],
            [
                $this->getColumnForQuery(),
                addslashes($this->filter)
            ],
            $this->getConditionTemplate()
        );
    }

    protected function getConditionTemplate()
    {
        return $this->templatesTable[$this->type];
    }
}
