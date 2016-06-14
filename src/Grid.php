<?php

namespace Sevavietl\GridCompanion;

use Sevavietl\GridCompanion\RowsInterval;

use DomainException;

use Sevavietl\GridCompanion\Contracts\DataProviderInterface;

use Sevavietl\GridCompanion\ColumnDefinitionsFactory;

abstract class Grid
{
    protected $schema;
    protected $baseFilterModel = [];

    protected $columnDefinitionsFactory;
    protected $columnDefinitions;

    protected $dataProvider;

    protected $queryParameters;

    protected $enabledColumnsIds = [];
    protected $disabledColumnsIds = [];

    public function __construct(DataProviderInterface $dataProvider)
    {
        $this->schema = $this->getSchema();

        $this->validateSchema();

        $this->baseFilterModel =

        $this->setColumnDefinitionsFactory();
        $this->setDataProvider($dataProvider);
    }

    abstract public function getSchema();

    protected function validateSchema()
    {
        if (!isset($this->schema['model'])) {
            throw new DomainException('There is no "model" specified in the schema.');
        }

        if (!isset($this->schema['alias'])) {
            throw new DomainException('There is no "alias" specified in the schema.');
        }
    }

    protected function setColumnDefinitionsFactory()
    {
        $this->columnDefinitionsFactory = new ColumnDefinitionsFactory;
    }

    protected function setColumnDefinitions()
    {
        if (empty($this->columnDefinitions)) {
            $this->columnDefinitions = $this->columnDefinitionsFactory
                ->build($this->schema);
        }
    }

    protected function setQueryParameters()
    {
        $this->queryParameters = new QueryParameters($this->columnDefinitions);
    }

    protected function readFromInput()
    {
        $params = json_decode(
            stripslashes(file_get_contents("php://input")),
            true
        );

        return is_array($params) ? $params : [];
    }

    public function setDataProvider(DataProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;

        return $this;
    }

    public function setBaseFilterModel(array $baseFilterModel)
    {
        $this->baseFilterModel = $baseFilterModel;

        return $this;
    }

    public function getData()
    {
        $this->setColumnDefinitions();
        $this->setQueryParameters();

        $params = $this->readFromInput();

        $this->setFilterModelFromRequest($params);
        $this->setSortModelFromRequest($params);
        $this->setRowsIntervalFromRequest($params);

        return $this->dataProvider->getData(
            $this->queryParameters->getQueryParameters()
        );
    }

    protected function setFilterModelFromRequest(array $params)
    {
        if (!empty($params['filterModel'])) {
            $this->setFilterModel($params['filterModel']);
        }
    }

    protected function setSortModelFromRequest(array $params)
    {
        if (!empty($params['sortModel'])) {
            $this->setSortModel($params['sortModel']);
        }
    }

    protected function setRowsIntervalFromRequest(array $params)
    {
        if (isset($params["startRow"]) && isset($params["endRow"])) {
            $this->setRowsInterval(
                new RowsInterval(
                    (int) $params["startRow"],
                    (int) $params["endRow"]
                )
            );
        }
    }

    public function getColumnDefinitions()
    {
        $this->setColumnDefinitions();

        return $this->columnDefinitions->toArray();
    }

    public function setRowsInterval(RowsInterval $rowsInterval)
    {
        $this->queryParameters->setRowsInterval($rowsInterval);

        return $this;
    }

    public function setFilterModel(array $filterModel)
    {
        $this->queryParameters->setFilters(
            array_merge($this->baseFilterModel, $filterModel)
        );

        return $this;
    }

    public function setSortModel(array $sortModel)
    {
        $this->queryParameters->setSort($sortModel);

        return $this;
    }

    public function setEnabledColumnIds(array $enabledColumnIds)
    {
        $this->columnDefinitionsFactory
            ->setEnabledColumnIds($enabledColumnIds);

        unset($this->columnDefinitions);

        return $this;
    }

    public function setDisabledColumnIds(array $disabledColumnIds)
    {
        $this->columnDefinitionsFactory
            ->setDisabledColumnIds($disabledColumnIds);

        unset($this->columnDefinitions);

        return $this;
    }
}
