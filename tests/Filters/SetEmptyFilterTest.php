<?php

use Sevavietl\GridCompanion\Filters\SetEmptyFilter;

class SetEmptyFilterTest extends TestCase
{
    protected $columnFilter;
    protected $hash;
    protected $setEmptyFilter;

    protected function setUp()
    {
        $this->columnFilter = [
            'statuses_status' => ['', 'Cancelled', 'Confirmed', 'Delivered']
        ];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'SetEmptyFilter'
        ];

        $this->setEmptyFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\SetEmptyFilter'
        )
        ->disableOriginalConstructor()
        ->getMock();
    }

    protected function tearDown()
    {
        unset($this->setEmptyFilter);
    }

    public function testSetFilter()
    {
        $this->setAttribute(
            $this->setEmptyFilter,
            'columnFilter',
            $this->columnFilter
        );

        $this->setAttribute(
            $this->setEmptyFilter,
            'columnId',
            'statuses_status'
        );

        $this->invokeMethod($this->setEmptyFilter, 'setFilter');

        $this->assertAttributeEquals(
            ['Cancelled', 'Confirmed', 'Delivered'],
            'filter',
            $this->setEmptyFilter
        );
        $this->assertAttributeEquals(true, 'includeEmpty', $this->setEmptyFilter);
    }

    public function testValidateFilter()
    {
        $this->setAttribute(
            $this->setEmptyFilter,
            'filter',
            ['Cancelled', 'Confirmed', 'Delivered']
        );

        $this->invokeMethod($this->setEmptyFilter, 'validateFilter');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateFilterWithNestedArray()
    {
        $this->setAttribute(
            $this->setEmptyFilter,
            'filter',
            [['Cancelled', 'Lost'], 'Confirmed', 'Delivered']
        );

        $this->invokeMethod($this->setEmptyFilter, 'validateFilter');
    }

    public function testInstantiation()
    {
        // Act
        $setEmptyFilter = new SetEmptyFilter($this->columnFilter, $this->hash);

        // Assert
        $this->assertNotNull($setEmptyFilter);
        $this->assertAttributeEquals(
            'statuses_status',
            'columnId',
            $setEmptyFilter
        );
        $this->assertAttributeEquals(
            ['Cancelled', 'Confirmed', 'Delivered'],
            'filter',
            $setEmptyFilter
        );
        $this->assertAttributeEquals(true, 'includeEmpty', $setEmptyFilter);
    }

    public function testGetCondition()
    {
        // Arrange
        $setEmptyFilter = new SetEmptyFilter($this->columnFilter, $this->hash);

        $expectedCondition = "Alias.column IS NULL OR Alias.column IN ('Cancelled', 'Confirmed', 'Delivered')";

        // Act
        $actualCondition = $this->invokeMethod(
            $setEmptyFilter,
            'getCondition'
        );

        // Assert
        $this->assertEquals($expectedCondition, $actualCondition);
    }

    public function testToArray()
    {
        // Arrange
        $setEmptyFilter = new SetEmptyFilter($this->columnFilter, $this->hash);

        $expectedArray = [
            'columnId' => 'statuses_status',
            'filter' => ['Cancelled', 'Confirmed', 'Delivered'],
            'condition' => "Alias.column IS NULL OR Alias.column IN ('Cancelled', 'Confirmed', 'Delivered')"
        ];

        // Act
        $actualArray = $setEmptyFilter->toArray();

        // Assert
        $this->assertEquals($expectedArray, $actualArray);
    }

    public function testToArrayNotIncludingEmpty()
    {
        // Arrange
        array_shift($this->columnFilter['statuses_status']);

        $setEmptyFilter = new SetEmptyFilter($this->columnFilter, $this->hash);

        $expectedArray = [
            'columnId' => 'statuses_status',
            'filter' => ['Cancelled', 'Confirmed', 'Delivered'],
            'condition' => "Alias.column IN ('Cancelled', 'Confirmed', 'Delivered')"
        ];

        // Act
        $actualArray = $setEmptyFilter->toArray();

        // Assert
        $this->assertEquals($expectedArray, $actualArray);
    }
}
