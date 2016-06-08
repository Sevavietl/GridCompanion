<?php

use Sevavietl\GridCompanion\Filters\TyppedFilter;

class TyppedFilterTest extends TestCase
{
    protected $columnFilter;

    protected $hash;

    protected $typpedFilter;

    protected function setUp()
    {
        $this->columnFilter = ['orders_days' => ['type' => 1, 'filter' => 1]];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'TextFilter'
        ];

        $this->typpedFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\TyppedFilter'
        )
        ->disableOriginalConstructor()
        ->getMockForAbstractClass();
    }

    protected function tearDown()
    {
        unset($this->typpedFilter);
    }

    public function testSetTypeAndFilter()
    {
        $this->setAttribute(
            $this->typpedFilter,
            'columnFilter',
            $this->columnFilter
        );

        $this->setAttribute(
            $this->typpedFilter,
            'columnId',
            'orders_days'
        );

        $this->invokeMethod($this->typpedFilter, 'setTypeAndFilter');

        $this->assertAttributeEquals(1, 'type', $this->typpedFilter);
        $this->assertAttributeEquals(1, 'filter', $this->typpedFilter);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTypeAndFilterWhenNoType()
    {
        unset($this->columnFilter['orders_days']['type']);

        $this->setAttribute(
            $this->typpedFilter,
            'columnFilter',
            $this->columnFilter
        );

        $this->setAttribute(
            $this->typpedFilter,
            'columnId',
            'orders_days'
        );

        $this->invokeMethod($this->typpedFilter, 'setTypeAndFilter');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetTypeAndFilterWhenNoFilter()
    {
        unset($this->columnFilter['orders_days']['filter']);

        $this->setAttribute(
            $this->typpedFilter,
            'columnFilter',
            $this->columnFilter
        );

        $this->setAttribute(
            $this->typpedFilter,
            'columnId',
            'orders_days'
        );

        $this->invokeMethod($this->typpedFilter, 'setTypeAndFilter');
    }

    public function testInstantiation()
    {
        // Arrange
        $typpedFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\TyppedFilter'
        )
        ->setMethods(['validateType', 'validateFilter'])
        ->disableOriginalConstructor()
        ->getMockForAbstractClass();

        $typpedFilter->expects($this->once())
        ->method('validateType');
        $typpedFilter->expects($this->once())
        ->method('validateFilter');

        // Act
        $typpedFilter->__construct($this->columnFilter, $this->hash);

        // Assert
        $this->assertNotNull($typpedFilter);
        $this->assertAttributeEquals(
            'orders_days',
            'columnId',
            $typpedFilter
        );
        $this->assertAttributeEquals(
            1,
            'type',
            $typpedFilter
        );
        $this->assertAttributeEquals(
            1,
            'filter',
            $typpedFilter
        );
    }

    public function testToArray()
    {
        // Arrange
        $typpedFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\TyppedFilter'
        )
        ->setMethods(['validateType', 'validateFilter', 'getCondition'])
        ->disableOriginalConstructor()
        ->getMockForAbstractClass();

        $typpedFilter->expects($this->once())
        ->method('validateType');
        $typpedFilter->expects($this->once())
        ->method('validateFilter');
        $typpedFilter->expects($this->once())
        ->method('getCondition')
        ->willReturn('Alias.column = 1');

        $typpedFilter->__construct($this->columnFilter, $this->hash);

        $expectedArray = [
            'columnId' => 'orders_days',
            'type' => 1,
            'filter' => 1,
            'condition' => 'Alias.column = 1'
        ];

        // Act
        $actualArray = $typpedFilter->toArray();

        // Assert
        $this->assertEquals($expectedArray, $actualArray);
    }
}
