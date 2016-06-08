<?php

use Sevavietl\GridCompanion\Filters\DateRangeFilter;

class DateRangeFilterTest extends TestCase
{
    protected $columnFilter;
    protected $hash;
    protected $dateRangeFilter;

    protected function setUp()
    {
        $this->columnFilter = [
            'orders_service_date' => ['from' => '2016-06-02', 'to' => '2016-06-02']
        ];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'DateRangeFilter'
        ];

        $this->dateRangeFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\DateRangeFilter'
        )
        ->disableOriginalConstructor()
        ->getMock();
    }

    protected function tearDown()
    {
        unset($this->dateRangeFilter);
    }

    public function testSetFilter()
    {
        $this->setAttribute(
            $this->dateRangeFilter,
            'columnFilter',
            $this->columnFilter
        );

        $this->setAttribute(
            $this->dateRangeFilter,
            'columnId',
            'orders_service_date'
        );

        $this->invokeMethod($this->dateRangeFilter, 'setFilter');

        $this->assertAttributeEquals(
            ['from' => '2016-06-02', 'to' => '2016-06-02'],
            'filter',
            $this->dateRangeFilter
        );
    }

    public function testValidateFilter()
    {
        $this->setAttribute(
            $this->dateRangeFilter,
            'filter',
            ['from' => '2016-06-02', 'to' => '2016-06-02']
        );

        $this->invokeMethod($this->dateRangeFilter, 'validateFilter');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateFilterWithoutFrom()
    {
        $this->setAttribute(
            $this->dateRangeFilter,
            'filter',
            ['to' => '2016-06-02']
        );

        $this->invokeMethod($this->dateRangeFilter, 'validateFilter');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateFilterWithoutTo()
    {
        $this->setAttribute(
            $this->dateRangeFilter,
            'filter',
            ['from' => '2016-06-02']
        );

        $this->invokeMethod($this->dateRangeFilter, 'validateFilter');
    }

    public function testInstantiation()
    {
        // Act
        $dateRangeFilter = new DateRangeFilter($this->columnFilter, $this->hash);

        // Assert
        $this->assertNotNull($dateRangeFilter);
        $this->assertAttributeEquals(
            'orders_service_date',
            'columnId',
            $dateRangeFilter
        );
        $this->assertAttributeEquals(
            ['from' => '2016-06-02', 'to' => '2016-06-02'],
            'filter',
            $dateRangeFilter
        );
    }

    public function testGetCondition()
    {
        // Arrange
        $dateRangeFilter = new DateRangeFilter($this->columnFilter, $this->hash);

        $expectedCondition = "Alias.column BETWEEN '2016-06-02' AND '2016-06-02'";

        // Act
        $actualCondition = $this->invokeMethod(
            $dateRangeFilter,
            'getCondition'
        );

        // Assert
        $this->assertEquals($expectedCondition, $actualCondition);
    }

    public function testGetConditionWithOnlyFrom()
    {
        // Arrange
        $columnFilter = $this->columnFilter;
        $columnFilter['orders_service_date']['to'] = "";

        $dateRangeFilter = new DateRangeFilter($columnFilter, $this->hash);

        $expectedCondition = "Alias.column >= '2016-06-02'";

        // Act
        $actualCondition = $this->invokeMethod(
            $dateRangeFilter,
            'getCondition'
        );

        // Assert
        $this->assertEquals($expectedCondition, $actualCondition);
    }

    public function testGetConditionWithOnlyTo()
    {
        // Arrange
        $columnFilter = $this->columnFilter;
        $columnFilter['orders_service_date']['from'] = "";

        $dateRangeFilter = new DateRangeFilter($columnFilter, $this->hash);

        $expectedCondition = "Alias.column <= '2016-06-02'";

        // Act
        $actualCondition = $this->invokeMethod(
            $dateRangeFilter,
            'getCondition'
        );

        // Assert
        $this->assertEquals($expectedCondition, $actualCondition);
    }

    public function testGetConditionWithEmptyFromAndTo()
    {
        // Arrange
        $columnFilter = $this->columnFilter;
        $columnFilter['orders_service_date']['from'] = "";
        $columnFilter['orders_service_date']['to'] = "";

        $dateRangeFilter = new DateRangeFilter($columnFilter, $this->hash);

        $expectedCondition = "";

        // Act
        $actualCondition = $this->invokeMethod(
            $dateRangeFilter,
            'getCondition'
        );

        // Assert
        $this->assertEquals($expectedCondition, $actualCondition);
    }

    public function testToArray()
    {
        // Arrange
        $dateRangeFilter = new DateRangeFilter($this->columnFilter, $this->hash);

        $expectedArray = [
            'columnId' => 'orders_service_date',
            'filter' => ['from' => '2016-06-02', 'to' => '2016-06-02'],
            'condition' => "Alias.column BETWEEN '2016-06-02' AND '2016-06-02'"
        ];

        // Act
        $actualArray = $dateRangeFilter->toArray();

        // Assert
        $this->assertEquals($expectedArray, $actualArray);
    }
}
