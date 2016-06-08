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
        $columnFilter = ['orders_days' => ['type' => 1, 'filter' => 1]];

        $hash = [
            'filterType' => 'number'
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
        $columnFilter = ['orders_days' => ['type' => 1, 'filter' => 'abc']];

        $hash = [
            'filterType' => 'text'
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
            'filterType' => 'set'
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
            'filterType' => 'SetEmptyFilter'
        ];

        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\GridCompanion\\Filters\\SetEmptyFilter',
            $filter
        );
    }

    public function testBuildDateRangeFilter()
    {
        // Arrange
        $columnFilter = ['orders_service_date' => ['from' => '2016-06-02', 'to' => '2016-06-02']];

        $hash = [
            'filterType' => 'DateRangeFilter'
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\GridCompanion\\Filters\\DateRangeFilter',
            $filter
        );
    }

    public function testBuildDateTimeFilter()
    {
        // Arrange
        $columnFilter = ['orders_created' => ['type' => 1, 'filter' => '2016-06-02 05:46']];

        $hash = [
            'filterType' => 'DateTimeFilter'
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\GridCompanion\\Filters\\DateTimeFilter',
            $filter
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testBuildFilterOfWrongType()
    {
        // Arrange
        $columnFilter = ['orders_days' => ['type' => 1, 'filter' => 'abc']];

        $hash = [
            'filterType' => 'foo'
        ];
        $columnId = key($columnFilter);
        $params = $columnFilter[$columnId];

        // Act
        $filter = $this->filterFactory->build($hash, $columnId, $params);
    }
}
