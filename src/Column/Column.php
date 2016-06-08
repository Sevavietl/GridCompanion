<?php

namespace Sevavietl\GridCompanion\Column;

use Sevavietl\GridCompanion\Contracts\ColumnDefinition;

use Sevavietl\GridCompanion\Column\Properties;
use Sevavietl\GridCompanion\Column\DefinitionTypeChecker;

use Sevavietl\GridCompanion\Column\PropertiesStorage;

use Sevavietl\GridCompanion\Column\Properties\Field;

class Column implements ColumnDefinition
{
    use Properties;
    use DefinitionTypeChecker;

    /**
     * Model for the column.
     * @var string
     */
    protected $model;

    /**
     * Alias for the column in join.
     * @var string
     */
    protected $alias;

    /**
     * Name of the column in the table.
     * @var [type]
     */
    protected $columnName;

    protected $columnAlias;

    /**
     * [__construct description]
     * @param string $model      [description]
     * @param string $alias      [description]
     * @param string $columnName [description]
     */
    public function __construct($model, $alias, $columnName){
        $field = new Field($alias, $columnName);

        $this->model = $model;
        $this->alias = $alias;
        $this->columnName = $columnName;
        $this->columnAlias = $field->getValue();

        $this->properties = new PropertiesStorage;
        $this->properties->attach($field);
    }

    /**
     * [getModel description]
     * @param string $defaultModel
     * @return string [description]
     */
    public function getModel($defaultModel = null)
    {
        return is_null($this->model) ? $defaultModel : $this->model;
    }

    /**
     * [getAlias description]
     * @param string $defaultAlias
     * @return string [description]
     */
    public function getAlias($defaultAlias = null)
    {
        return is_null($this->alias) ? $defaultAlias : $this->alias;
    }

    /**
     * [getColumnName description]
     * @return string [description]
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    public function getColumnAlias()
    {
        return $this->columnAlias;
    }
}
