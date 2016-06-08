<?php

namespace Sevavietl\GridCompanion\Column;

use Sevavietl\GridCompanion\Contracts\ColumnDefinition;

use SplQueue;

use Sevavietl\GridCompanion\Column\Properties;
use Sevavietl\GridCompanion\Column\DefinitionTypeChecker;

use Sevavietl\GridCompanion\Column\PropertiesStorage;

use Sevavietl\GridCompanion\Column\Properties\Field;

class ColumnGroup implements ColumnDefinition
{
    use Properties {
        toArray as propertiesToArray;
    }
    use DefinitionTypeChecker;

    protected $columns;

    public function __construct()
    {
        $this->properties = new PropertiesStorage;
        $this->columns = new SplQueue;
    }

    public function addColumn(ColumnDefinition $column)
    {
        $this->columns->enqueue($column);

        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function toArray()
    {
        return array_merge(
            $this->propertiesToArray(),
            $this->childrenToArray()
        );
    }

    protected function childrenToArray()
    {
        return [
            'children' => reduce(
                function ($column, $carry) {
                    array_push($carry, $column->toArray());
                    return $carry;
                },
                [],
                $this->columns
            )
        ];
    }
}
