<?php

use Sevavietl\GridCompanion\FilterFactory;

class FilterFactoryTest extends TestCase
{
    protected $filterFactory;

    protected function setUp()
    {
        $this->filterFactory = new FilterFactory;
    }

    protected function tearDown()
    {
        unset($this->filterFactory);
    }

    public function testBuildNumberFilter()
    {
        // Arrange
        $columnFilter = ['orders_days' => ['type' => 'equals', 'filter' => 1]];

        $hash = [
            'orders_days' => ['filterType' => 'number']
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\GridCompanion\\Filters\\NumberFilter',
            $filter
        );
    }

    public function testBuildTextFilter()
    {
        // Arrange
        $columnFilter = ['orders_days' => ['type' => 'contains', 'filter' => 'abc']];

        $hash = [
            'orders_days' => ['filterType' => 'text']
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\GridCompanion\\Filters\\TextFilter',
            $filter
        );
    }

    public function testBuildSetFilter()
    {
        // Arrange
        $columnFilter = ['statuses_status' => ['Cancelled', 'Confirmed', 'Delivered']];

        $hash = [
            'statuses_status' => ['filterType' => 'set']
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\GridCompanion\\Filters\\SetFilter',
            $filter
        );
    }

    public function testBuildSetEmptyFilter()
    {
        // Arrange
        $columnFilter = ['statuses_status' => ['', 'Cancelled', 'Confirmed', 'Delivered']];

        $hash = [
            'statuses_status' => ['filterType' => 'Sevavietl\\GridCompanion\\Filters\\SetEmptyFilter']
        ];

        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\\GridCompanion\\Filters\\SetEmptyFilter',
            $filter
        );
    }

    public function testBuildDateRangeFilter()
    {
        // Arrange
        $columnFilter = ['orders_service_date' => ['from' => '2016-06-02', 'to' => '2016-06-02']];

        $hash = [
            'orders_service_date' => ['filterType' => 'Sevavietl\\GridCompanion\\Filters\\DateRangeFilter']
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\\GridCompanion\\Filters\\DateRangeFilter',
            $filter
        );
    }

    public function testBuildDateTimeFilter()
    {
        // Arrange
        $columnFilter = ['orders_created' => ['type' => 'equals','filter' => '2016-06-02 05:46']];

        $hash = [
            'orders_created' => ['filterType' => 'Sevavietl\\GridCompanion\\Filters\\DateTimeFilter']
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\\GridCompanion\\Filters\\DateTimeFilter',
            $filter
        );
    }

    public function testMultipleColumnsFilter()
    {
        // Arrange
        $columnFilter = ['["orders_days", "days"]' => ['type' => 'equals', 'filter' => 1]];

        $hash = [
            'orders_days' => ['filterType' => 'number'],
            'days'        => ['filterType' => 'number']
        ];

        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\\GridCompanion\\Filters\\MultipleColumnsFilter',
            $filter
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testBuildFilterOfWrongType()
    {
        // Arrange
        $columnFilter = ['orders_days' => ['type' => 'equals', 'filter' => 'abc']];

        $hash = [
            'orders_days' => ['filterType' => 'foo']
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);
    }
}
