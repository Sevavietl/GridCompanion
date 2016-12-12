<?php

use Sevavietl\GridCompanion\Filters\MultipleColumnsFilter;

class MultipleColumnsFilterTest extends TestCase
{

    public function testSetColumnFilters()
    {
        // Arrange
        $multipleColumnsFilter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Filters\MultipleColumnsFilter'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $columnIds = ['column1', 'column2'];
        $filter = [
            'type' => 1,
            'filter' => '12345',
            'filterType' => 'number'
        ];

        $this->setAttribute($multipleColumnsFilter, 'columnIds', $columnIds);
        $this->setAttribute($multipleColumnsFilter, 'filter', $filter);

        $columnFilter1 = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Filters\NumberFilter'
        )
        ->disableOriginalConstructor()
        ->setMethods(['getColumnForQuery'])
        ->getMock();

        $columnFilter1->expects($this->any())
            ->method('getColumnForQuery')
            ->willReturn('Alias1.column1');

        $columnFilter2 = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Filters\NumberFilter'
        )
        ->disableOriginalConstructor()
        ->setMethods(['getColumnForQuery'])
        ->getMock();

        $columnFilter2->expects($this->any())
            ->method('getColumnForQuery')
            ->willReturn('Alias2.column2');

        $filterFactory = $this->getMockBuilder(
            'Sevavietl\GridCompanion\FilterFactory'
        )
        ->setMethods(['build'])
        ->getMock();

        $hash = [
            'column1' => [
                'model' => 'Model1',
                'alias' => 'Alias1',
                'column' => 'column1',
                'filterType' => 'number'
            ],
            'column2' => [
                'model' => 'Model2',
                'alias' => 'Alias2',
                'column' => 'column2',
                'filterType' => 'number'
            ]
        ];

        $this->setAttribute($multipleColumnsFilter, 'hash', $hash);

        $filtersMap = [
            [$hash, 'column1', $filter, $columnFilter1],
            [$hash, 'column2', $filter, $columnFilter2]
        ];

        $filterFactory->expects($this->exactly(2))
            ->method('build')
            ->will($this->returnValueMap($filtersMap));

        $this->setAttribute($multipleColumnsFilter, 'filterFactory', $filterFactory);

        // Act
        $this->invokeMethod($multipleColumnsFilter, 'setColumnFilters');

        // Assert
        $this->assertAttributeEquals(
            [$columnFilter1, $columnFilter2],
            'columnFilters',
            $multipleColumnsFilter
        );
    }

    public function testInstantiation()
    {
        // Arrange
        $columnIds = ['column1', 'column2'];
        $filter = [
            'type' => 'equals',
            'filter' => '12345',
            'filterType' => 'number'
        ];
        $hash = [
            'column1' => [
                'model' => 'Model1',
                'alias' => 'Alias1',
                'column' => 'column1',
                'filterType' => 'number'
            ],
            'column2' => [
                'model' => 'Model2',
                'alias' => 'Alias2',
                'column' => 'column2',
                'filterType' => 'number'
            ]
        ];

        // Act
        $multipleColumnsFilter = new MultipleColumnsFilter($columnIds, $filter, $hash);

        // Assert
        $this->assertAttributeEquals(
            $columnIds,
            'columnIds',
            $multipleColumnsFilter
        );
        $this->assertAttributeEquals(
            $filter,
            'filter',
            $multipleColumnsFilter
        );
        $this->assertAttributeEquals(
            $hash,
            'hash',
            $multipleColumnsFilter
        );
        $this->assertAttributeInstanceOf(
            'Sevavietl\GridCompanion\FilterFactory',
            'filterFactory',
            $multipleColumnsFilter
        );
    }

    public function testGetCondition()
    {
        // Arrange
        $columnIds = ['column1', 'column2'];
        $filter = [
            'type' => 'equals',
            'filter' => '12345',
            'filterType' => 'number'
        ];
        $hash = [
            'column1' => [
                'model' => 'Model1',
                'alias' => 'Alias1',
                'column' => 'column1',
                'filterType' => 'number'
            ],
            'column2' => [
                'model' => 'Model2',
                'alias' => 'Alias2',
                'column' => 'column2',
                'filterType' => 'number'
            ]
        ];
        $multipleColumnsFilter = new MultipleColumnsFilter($columnIds, $filter, $hash);

        $expectedCondition = "(Alias1.column1 = 12345 OR Alias2.column2 = 12345)";

        // Act
        $actualCondition = $this->invokeMethod($multipleColumnsFilter, 'getCondition');

        // Assert
        $this->assertEquals($expectedCondition, $actualCondition);
    }

    public function testToArray()
    {
        // Arrange
        $columnIds = ['column1', 'column2'];
        $filter = [
            'type' => 'equals',
            'filter' => '12345',
            'filterType' => 'number'
        ];
        $hash = [
            'column1' => [
                'model' => 'Model1',
                'alias' => 'Alias1',
                'column' => 'column1',
                'filterType' => 'number'
            ],
            'column2' => [
                'model' => 'Model2',
                'alias' => 'Alias2',
                'column' => 'column2',
                'filterType' => 'number'
            ]
        ];

        $multipleColumnsFilter = new MultipleColumnsFilter($columnIds, $filter, $hash);
        $expectedArray = [
            'columnId' => $columnIds,
            'filter' => $filter,
            'condition' => "(Alias1.column1 = 12345 OR Alias2.column2 = 12345)"
        ];

        // Act
        $actualArray = $multipleColumnsFilter->toArray();

        // Arrange
        $this->assertEquals($expectedArray, $actualArray);
    }
}
