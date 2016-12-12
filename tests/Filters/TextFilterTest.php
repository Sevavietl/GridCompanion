<?php

use Sevavietl\GridCompanion\Filters\TextFilter;

class TextFilterTest extends TestCase
{
    protected $columnFilter;

    protected $hash;

    protected $textFilter;

    protected function setUp()
    {
        $this->columnFilter = ['orders_days' => ['type' => 'contains', 'filter' => 'abc']];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'TextFilter'
        ];

        $this->textFilter = new TextFilter(
            $this->columnFilter,
            $this->hash
        );
    }

    protected function tearDown()
    {
        unset($this->textFilter);
    }

    public function testValidateType()
    {
        $this->invokeMethod($this->textFilter, 'validateType');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateTypeWhenNotAllowedType()
    {
        $this->setAttribute(
            $this->textFilter,
            'type',
            'fooBarBaz'
        );

        $this->invokeMethod($this->textFilter, 'validateType');
    }

    public function testValidateFilter()
    {
        $this->setAttribute(
            $this->textFilter,
            'filter',
            'abc'
        );

        $this->invokeMethod($this->textFilter, 'validateFilter');
    }

    public function testInstantiation()
    {
        // Act
        $textFilter = new TextFilter($this->columnFilter, $this->hash);

        // Assert
        $this->assertNotNull($textFilter);
        $this->assertAttributeEquals(
            'orders_days',
            'columnId',
            $textFilter
        );
        $this->assertAttributeEquals(
            'contains',
            'type',
            $textFilter
        );
        $this->assertAttributeEquals(
            'abc',
            'filter',
            $textFilter
        );
    }

    public function testGetConditionContains()
    {
        $condition = $this->invokeMethod($this->textFilter, 'getCondition');

        $this->assertEquals('Alias.column LIKE \'%abc%\'', $condition);
    }

    public function testGetConditionEquals()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'equals';

        $textFilter = new TextFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($textFilter, 'getCondition');

        $this->assertEquals('Alias.column = \'abc\'', $condition);
    }

    public function testGetConditionNotEquals()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'notEquals';

        $textFilter = new TextFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($textFilter, 'getCondition');

        $this->assertEquals('Alias.column <> \'abc\'', $condition);
    }

    public function testGetConditionStartsWith()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'startsWith';

        $textFilter = new TextFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($textFilter, 'getCondition');

        $this->assertEquals('Alias.column LIKE \'abc%\'', $condition);
    }

    public function testGetConditionEndsWith()
    {
        $this->columnFilter[key($this->columnFilter)]['type'] = 'endsWith';

        $textFilter = new TextFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($textFilter, 'getCondition');

        $this->assertEquals('Alias.column LIKE \'%abc\'', $condition);
    }
}
