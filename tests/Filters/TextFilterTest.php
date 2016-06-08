<?php

use Sevavietl\GridCompanion\Filters\TextFilter;

class TextFilterTest extends TestCase
{
    protected $columnFilter;

    protected $hash;

    protected $textFilter;

    protected function setUp()
    {
        $this->columnFilter = ['orders_days' => ['type' => 1, 'filter' => 'abc']];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'TextFilter'
        ];

        $textFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\TextFilter'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $this->textFilter = $textFilter;
    }

    protected function tearDown()
    {
        unset($this->textFilter);
    }

    public function testValidateType()
    {
        $this->setAttribute(
            $this->textFilter,
            'type',
            1
        );

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
            7
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
            1,
            'type',
            $textFilter
        );
        $this->assertAttributeEquals(
            'abc',
            'filter',
            $textFilter
        );
    }

    public function testGetCondition()
    {
        $textFilter = new TextFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($textFilter, 'getCondition');

        $this->assertEquals('Alias.column LIKE \'%abc%\'', $condition);
    }
}
