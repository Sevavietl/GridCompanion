<?php

use Sevavietl\GridCompanion\Filters\DateTimeFilter;

class DateTimeFilterTest extends TestCase
{
    protected $columnFilter;
    protected $hash;

    protected $dateTimeFilter;

    protected function setUp()
    {
        $this->columnFilter = ['orders_created' => ['type' => 1, 'filter' => '2016-06-02 05:46']];

        $this->hash = [
            'model' => 'Model',
            'alias' => 'Alias',
            'column' => 'column',
            'filterType' => 'DateTimeFilter'
        ];

        $dateTimeFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Filters\\DateTimeFilter'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $this->dateTimeFilter = $dateTimeFilter;
    }

    protected function tearDown()
    {
        unset($this->dateTimeFilter);
    }

    public function testValidateType()
    {
        $this->setAttribute(
            $this->dateTimeFilter,
            'type',
            1
        );

        $this->invokeMethod($this->dateTimeFilter, 'validateType');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateTypeWhenNotAllowedType()
    {
        $this->setAttribute(
            $this->dateTimeFilter,
            'type',
            5
        );

        $this->invokeMethod($this->dateTimeFilter, 'validateType');
    }

    public function testValidateFilter()
    {
        $this->setAttribute(
            $this->dateTimeFilter,
            'filter',
            '2016-06-02 05:46'
        );

        $this->invokeMethod($this->dateTimeFilter, 'validateFilter');
    }

    /**
     * @expectedException DomainException
     */
    public function testValidateFilterWithIncorrectDate()
    {
        $this->setAttribute(
            $this->dateTimeFilter,
            'filter',
            '2016-06-02 05:'
        );

        $this->invokeMethod($this->dateTimeFilter, 'validateFilter');
    }

    public function testInstantiation()
    {
        // Act
        $dateTimeFilter = new DateTimeFilter($this->columnFilter, $this->hash);

        // Assert
        $this->assertNotNull($dateTimeFilter);
        $this->assertAttributeEquals(
            'orders_created',
            'columnId',
            $dateTimeFilter
        );
        $this->assertAttributeEquals(
            1,
            'type',
            $dateTimeFilter
        );
        $this->assertAttributeEquals(
            '2016-06-02 05:46',
            'filter',
            $dateTimeFilter
        );
    }

    public function testGetCondition()
    {
        $dateTimeFilter = new DateTimeFilter($this->columnFilter, $this->hash);

        $condition = $this->invokeMethod($dateTimeFilter, 'getCondition');

        $this->assertEquals('Alias.column LIKE \'2016-06-02 05:46%\'', $condition);
    }
}
