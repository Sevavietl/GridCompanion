<?php

use Sevavietl\GridCompanion\Filters\SetFilter;

class SetFilterTest extends TestCase
{
    protected $columnFilter;
    protected $hash;
    protected $setFilter;

    protected function setUp()
    {
        $this->columnFilter = [
            'statuses_status' => ['Cancelled', 'Confirmed', 'Delivered']
        ];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'NumberFilter'
        ];

        $this->setFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\SetFilter'
        )
        ->disableOriginalConstructor()
        ->getMock();
    }

    protected function tearDown()
    {
        unset($this->setFilter);
    }

    public function testSetFilter()
    {
        $this->setAttribute(
            $this->setFilter,
            'columnFilter',
            $this->columnFilter
        );

        $this->setAttribute(
            $this->setFilter,
            'columnId',
            'statuses_status'
        );

        $this->invokeMethod($this->setFilter, 'setFilter');

        $this->assertAttributeEquals(
            ['Cancelled', 'Confirmed', 'Delivered'],
            'filter',
            $this->setFilter
        );
    }

    public function testValidateFilter()
    {
        $this->setAttribute(
            $this->setFilter,
            'filter',
            ['Cancelled', 'Confirmed', 'Delivered']
        );

        $this->invokeMethod($this->setFilter, 'validateFilter');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateFilterWithNestedArray()
    {
        $this->setAttribute(
            $this->setFilter,
            'filter',
            [['Cancelled', 'Lost'], 'Confirmed', 'Delivered']
        );

        $this->invokeMethod($this->setFilter, 'validateFilter');
    }

    public function testInstantiation()
    {
        // Act
        $setFilter = new SetFilter($this->columnFilter, $this->hash);

        // Assert
        $this->assertNotNull($setFilter);
        $this->assertAttributeEquals(
            'statuses_status',
            'columnId',
            $setFilter
        );
        $this->assertAttributeEquals(
            ['Cancelled', 'Confirmed', 'Delivered'],
            'filter',
            $setFilter
        );
    }

    public function testGetCondition()
    {
        // Arrange
        $setFilter = new SetFilter($this->columnFilter, $this->hash);

        $expectedCondition = "Alias.column IN ('Cancelled', 'Confirmed', 'Delivered')";

        // Act
        $actualCondition = $this->invokeMethod(
            $setFilter,
            'getCondition'
        );

        // Assert
        $this->assertEquals($expectedCondition, $actualCondition);
    }

    public function testToArray()
    {
        // Arrange
        $setFilter = new SetFilter($this->columnFilter, $this->hash);

        $expectedArray = [
            'columnId' => 'statuses_status',
            'filter' => ['Cancelled', 'Confirmed', 'Delivered'],
            'condition' => "Alias.column IN ('Cancelled', 'Confirmed', 'Delivered')"
        ];

        // Act
        $actualArray = $setFilter->toArray();

        // Assert
        $this->assertEquals($expectedArray, $actualArray);
    }
}
