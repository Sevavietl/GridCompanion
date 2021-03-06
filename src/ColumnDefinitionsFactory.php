<?php

namespace Sevavietl\GridCompanion;

use Sevavietl\GridCompanion\ColumnDefinitions;

use Sevavietl\GridCompanion\Contracts\ColumnDefinition;

use Sevavietl\GridCompanion\Column\Column;
use Sevavietl\GridCompanion\Column\ColumnGroup;

use Sevavietl\GridCompanion\Column\Properties\SimpleProperty;
use Sevavietl\GridCompanion\Column\Properties\Filter;

use DomainException;

class ColumnDefinitionsFactory
{
    protected $propertyNamespace = 'Sevavietl\GridCompanion\Column\Properties\\';

    protected $model;
    protected $alias;

    protected $schema;

    protected $enabledColumnIds = [];
    protected $disabledColumnIds = [];

    protected $currentColumn = null;
    protected $currentHash = null;

    protected $complexProperties = [
        'field', 'filter', 'filterFramework'
    ];

    public function setEnabledColumnIds(array $enabledColumnIds)
    {
        $this->enabledColumnIds = $enabledColumnIds;

        return $this;
    }

    public function setDisabledColumnIds(array $disabledColumnIds)
    {
        $this->disabledColumnIds = $disabledColumnIds;

        return $this;
    }

    public function build(array $schema)
    {
        $this->schema = $schema;

        $this->setUpModelAndAlias();

        $columnDefinitions = new ColumnDefinitions($this->model, $this->alias);

        $this->addColumnGroupsAndColumns(
            $this->separateColumnDefinitionsSchema(),
            function ($column) use (&$columnDefinitions) {
                if (!empty($column)) {
                    $columnDefinitions->add($column, $this->currentHash);
                }
            }
        );

        $this->clearDown();

        return $columnDefinitions;
    }

    protected function setUpModelAndAlias()
    {
        if (!isset($this->schema['model'])) {
            throw new DomainException("No main model specified in the schema");
        }
        $this->model = $this->schema['model'];

        if (!isset($this->schema['alias'])) {
            throw new DomainException("No main alias specified in the schema");
        }
        $this->alias = $this->schema['alias'];
    }

    protected function clearDown()
    {
        $this->model = null;
        $this->alias = null;
        $this->schema = null;
    }

    protected function separateColumnDefinitionsSchema()
    {
        $schema = $this->schema;
        // This will work in PHP 5.6+
        // return array_filter($schema, 'is_integer', ARRAY_FILTER_USE_KEY);

        return array_reduce(
            array_filter(array_keys($schema), 'is_integer'),
            function ($carry, $item) use ($schema) {
                array_push($carry, $schema[$item]);
                return $carry;
            },
            []
        );
    }

    protected function buildColumn(array $columnDefinition)
    {
        $this->validateColumnDefinition($columnDefinition);

        if (!isset($columnDefinition['model'])) {
            $columnDefinition['model'] = $this->model;
        }
        if (!isset($columnDefinition['alias'])) {
            $columnDefinition['alias'] = $this->alias;
        }

        if (
            !$this->isColumnEnabled($columnDefinition)
            || $this->isColumnDisabled($columnDefinition)
        ) {
            return null;
        }

        $model = $columnDefinition['model'];
        $alias = $columnDefinition['alias'];
        $field = $columnDefinition['field'];
        unset($columnDefinition['model']);
        unset($columnDefinition['alias']);
        unset($columnDefinition['field']);

        $this->currentColumn = new Column($model, $alias, $field);
        $this->hashCurrentColumn();

        $this->addProperties($this->currentColumn, $columnDefinition);

        return $this->currentColumn;
    }

    protected function validateColumnDefinition(array $columnDefinition)
    {
        if (!isset($columnDefinition['field'])) {
            throw new DomainException("The 'field' definition is necessary.");
        }
    }

    protected function isColumnEnabled(array $columnDefinition)
    {
        if (empty($this->enabledColumnIds)) {
            return true;
        }

        if (!isset($columnDefinition['id'])) {
            $columnDefinition['id'] = uncamelize(
                $columnDefinition['alias'] . '_' . $columnDefinition['field']
            );
        }

        return in_array($columnDefinition['id'], $this->enabledColumnIds);
    }

    protected function isColumnDisabled(array $columnDefinition)
    {
        if (empty($this->disabledColumnIds)) {
            return false;
        }

        if (!isset($columnDefinition['id'])) {
            $columnDefinition['id'] = uncamelize(
                $columnDefinition['alias'] . '_' . $columnDefinition['field']
            );
        }

        return in_array($columnDefinition['id'], $this->disabledColumnIds);
    }

    protected function hashCurrentColumn()
    {
        if (!empty($this->currentColumn)) {
            $this->currentHash[$this->currentColumn->getColumnAlias()] = [
                'model' => $this->currentColumn->getModel(),
                'alias' => $this->currentColumn->getAlias(),
                'column' => $this->currentColumn->getColumnName()
            ];
        }
    }

    protected function buildColumnGroup(array $columnGroupDefinition)
    {
        $this->validateColumnGroupDefinition($columnGroupDefinition);

        $columnDefinitions = $columnGroupDefinition['children'];
        unset($columnGroupDefinition['children']);

        $columnGroup = new ColumnGroup;

        unset($this->currentColumn);

        $this->addProperties($columnGroup, $columnGroupDefinition);
        $this->addColumnGroupsAndColumns(
            $columnDefinitions,
            function ($column) use (&$columnGroup) {
                if (!empty($column)) {
                    $columnGroup->addColumn($column);
                }
            }
        );

        return $columnGroup;
    }

    protected function validateColumnGroupDefinition(array $columnGroupDefinition)
    {
        if (!isset($columnGroupDefinition['children'])) {
            throw new DomainException("The 'children' property is necessary for column group.");
        }
    }

    protected function addProperties(ColumnDefinition &$columnDefinition, $properties)
    {
        foreach ($properties as $key => $value) {
            $columnDefinition->addProperty($this->buildProperty($key, $value));
        }
    }

    protected function buildProperty($key, $value)
    {
        if (!in_array($key, $this->complexProperties)) {
            return new SimpleProperty($key, $value);
        }

        switch ($key) {
            case 'filter':
            case 'filterFramework':
                if (
                    !empty($this->currentColumn)
                    && !empty($this->currentHash[$this->currentColumn->getColumnAlias()])
                    && !empty($value['type'])
                ) {
                    $this->currentHash[$this->currentColumn->getColumnAlias()]['filterType'] = $value['type'];
                }

                $type = isset($value['type']) ? $value['type'] : null;
                $params = isset($value['params']) ? $value['params'] : null;

                $filter = $this->propertyNamespace . ucfirst($key);
                return is_null($params) ? (new $filter($type)) : (new $filter($type, $params));

            default:
                throw new DomainException("There is no property $key.");
        }
    }

    protected function addColumnGroupsAndColumns(
        $columnDefinitions,
        callable $addingMethod
    ) {
        foreach ($columnDefinitions as $definition) {
            if ($this->isColumnGroup($definition)) {
                $addingMethod($this->buildColumnGroup($definition));
                continue;
            }

            $addingMethod($this->buildColumn($definition));
        }
    }

    protected function isColumnGroup($definition)
    {
        return isset($definition['children']);
    }
}
