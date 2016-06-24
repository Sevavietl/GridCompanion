<?php

use Sevavietl\GridCompanion\Column\Properties\Filter;

class FilterTest extends TestCase
{
    public function testSetFilterInstantiation()
    {
        // Arrange
        $type       = 'set';
        $params = [
            'values'        => [],
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        $expectedType       = 'set';
        $expectedParams = [
            'values'        => [],
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        // Act
        $filter = new Filter($type, $params);

        // Assert
        $this->assertEquals(
            [$filter->getType(), $filter->getParams()],
            [$expectedType, $expectedParams]
        );
    }

    public function testNumberFilterInstantiation()
    {
        // Arrange
        $type   = 'number';
        $params = [
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        $expectedType       = 'number';
        $expectedParams = [
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        // Act
        $filter = new Filter($type, $params);

        // Assert
        $this->assertEquals(
            [$filter->getType(), $filter->getParams()],
            [$expectedType, $expectedParams]
        );
    }

    public function testTextFilterInstantiation()
    {
        // Arrange
        $type   = 'text';
        $params = [
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        $expectedType   = 'text';
        $expectedParams = [
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        // Act
        $filter = new Filter($type, $params);

        // Assert
        $this->assertEquals(
            [$filter->getType(), $filter->getParams()],
            [$expectedType, $expectedParams]
        );
    }

    public function testNoFilterParamsInstantiation()
    {
        // Arrange
        $type = 'text';

        $expectedType   = 'text';
        $expectedParams = [];

        // Act
        $filter = new Filter($type);

        // Assert
        $this->assertEquals(
            [$filter->getType(), $filter->getParams()],
            [$expectedType, $expectedParams]
        );
    }

    public function testEmptyFilterParamsInstantiation()
    {
        // Arrange
        $type   = 'text';
        $params = [];

        $expectedType   = 'text';
        $expectedParams = [];

        // Act
        $filter = new Filter($type, $params);

        // Assert
        $this->assertEquals(
            [$filter->getType(), $filter->getParams()],
            [$expectedType, $expectedParams]
        );
    }

    public function testToArray()
    {
        // Arrange
        $type   = 'set';
        $params = [
            'values'        => ['1', '2', '3'],
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        $expectedFilterArray = [
            'filter'       => 'set',
            'filterParams' => [
                'values'        => ['1', '2', '3'],
                'newRowsAction' => 'keep',
                'apply'         => true,
            ]
        ];

        // Act
        $filter = new Filter($type, $params);

        // Assert
        $this->assertEquals($filter->toArray(), $expectedFilterArray);
    }

    public function testToArrayWithNoFilterParams()
    {
        // Arrange
        $type = 'set';

        $expectedFilterArray = [
            'filter'       => 'set'
        ];

        // Act
        $filter = new Filter($type);

        // Assert
        $this->assertEquals($filter->toArray(), $expectedFilterArray);
    }

    public function testTryToResolveCallableValuesFromParams()
    {
        // Arrange
        $params = [
            'values'        => function () {
                return [1, 2, 3];
            },
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        $filter = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Column\Properties\Filter'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->setAttribute($filter, 'params', $params);

        $expectedParams = [
            'values'        => [1, 2, 3],
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        // Act
        $this->invokeMethod($filter, 'tryToResolveCallableValuesFromParams');

        // Assert
        $this->assertAttributeEquals($expectedParams, 'params', $filter);
    }

    public function testToArrayWithCallableAsValuesFilterParams()
    {
        // Arrange
        $type   = 'set';
        $params = [
            'values'        => function () {
                return [1, 2, 3];
            },
            'newRowsAction' => 'keep',
            'apply'         => true,
        ];

        $expectedFilterArray = [
            'filter'       => 'set',
            'filterParams' => [
                'values'        => [1, 2, 3],
                'newRowsAction' => 'keep',
                'apply'         => true,
            ]
        ];

        // Act
        $filter = new Filter($type, $params);

        // Assert
        $this->assertEquals($filter->toArray(), $expectedFilterArray);
    }
}
