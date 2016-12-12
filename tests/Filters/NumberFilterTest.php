<?php

use Sevavietl\GridCompanion\Filters\NumberFilter;

class NumberFilterTest extends TestCase
{
    protected $columnFilter;
    protected $hash;

    protected $numberFilter;

    protected function setUp()
    {
        $this->columnFilter = ['orders_days' => ['type' => 'equals', 'filter' => 1]];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'NumberFilter'
        ];

        $this->numberFilter = new NumberFilter($this->columnFilter, $this->hash);
    }

    protected function tearDown()
    {
        unset($this->numberFilter);
    }

    public function testValidateType()
    {
        $this->setAttribute(
            $this->numberFilter,
            'type',
            'equals'
        );

        $this->invokeMethod($this->numberFilter, 'validateType');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateTypeWhenNotAllowedType()
    {
        $this->setAttribute(
            $this->numberFilter,
            'type',
            'fooBarBaz'
        );

        $this->invokeMethod($this->numberFilter, 'validateType');
    }

    public function testValidateFilter()
    {
        $this->setAttribute(
            $this->numberFilter,
            'filter',
            1
        );

        $this->invokeMethod($this->numberFilter, 'validateFilter');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateFilterWhenNotNumericalFilterValue()
    {
        $this->setAttribute(
            $this->numberFilter,
            'filter',
            'abc'
        );

        $this->invokeMethod($this->numberFilter, 'validateFilter');
    }

    public function testInstantiation()
    {
        // Act
        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        // Assert
        $this->assertNotNull($numberFilter);
        $this->assertAttributeEquals(
            'orders_days',
            'columnId',
            $numberFilter
        );
        $this->assertAttributeEquals(
            'equals',
            'type',
            $numberFilter
        );
        $this->assertAttributeEquals(
            1,
            'filter',
            $numberFilter
        );
    }

    public function testGetConditionEquals()
    {
        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($numberFilter, 'getCondition');

        $this->assertEquals('Alias.column = 1', $condition);
    }

    public function testGetConditionNotEqual()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'notEqual';

        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($numberFilter, 'getCondition');

        $this->assertEquals('Alias.column <> 1', $condition);
    }

    public function testGetConditionLessThan()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'lessThan';

        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($numberFilter, 'getCondition');

        $this->assertEquals('Alias.column < 1', $condition);
    }

    public function testGetConditionLessThanOrEqual()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'lessThanOrEqual';

        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($numberFilter, 'getCondition');

        $this->assertEquals('Alias.column <= 1', $condition);
    }

    public function testGetConditionGreaterThan()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'greaterThan';

        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($numberFilter, 'getCondition');

        $this->assertEquals('Alias.column > 1', $condition);
    }

    public function testGetConditionGreaterThanOrEqual()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'greaterThanOrEqual';

        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($numberFilter, 'getCondition');

        $this->assertEquals('Alias.column >= 1', $condition);
    }
}
