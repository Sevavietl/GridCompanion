<?php

use Sevavietl\GridCompanion\Filters\NumberFilter;

class NumberFilterTest extends TestCase
{
    protected $columnFilter;
    protected $hash;

    protected $numberFilter;

    protected function setUp()
    {
        $this->columnFilter = ['orders_days' => ['type' => 1, 'filter' => 1]];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'NumberFilter'
        ];

        $numberFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\NumberFilter'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $this->numberFilter = $numberFilter;
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
            1
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
            5
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
            1,
            'type',
            $numberFilter
        );
        $this->assertAttributeEquals(
            1,
            'filter',
            $numberFilter
        );
    }

    public function testGetCondition()
    {
        $numberFilter = new NumberFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($numberFilter, 'getCondition');

        $this->assertEquals('Alias.column = 1', $condition);
    }
}
