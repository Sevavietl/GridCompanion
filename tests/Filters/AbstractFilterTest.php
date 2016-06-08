<?php

use Sevavietl\GridCompanion\Filters\Filter;

class AbstractFilterTest extends TestCase
{
    protected $columnFilter;

    protected $hash;

    protected $filter;

    protected function setUp()
    {
        $this->columnFilter = ['orders_days' => ['type' => 1, 'filter' => 1]];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'text'
        ];

        $this->filter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\Filter'
        )
        ->disableOriginalConstructor()
        ->getMockForAbstractClass();
    }

    protected function tearDown()
    {
        unset($this->filter);
    }

    public function testSetColumnId()
    {
        $this->setAttribute(
            $this->filter,
            'columnFilter',
            $this->columnFilter
        );

        $this->invokeMethod($this->filter, 'setColumnId');

        $this->assertAttributeEquals(
            'orders_days',
            'columnId',
            $this->filter
        );
    }

    public function testGetColumnForQuery()
    {
        // Arrange
        $this->setAttribute(
            $this->filter,
            'hash',
            $this->hash
        );

        // Act
        $columnForQuery = $this->invokeMethod($this->filter, 'getColumnForQuery');

        // Assert
        $this->assertEquals('Alias.column', $columnForQuery);
    }
}
