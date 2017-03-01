<?php

use Sevavietl\GridCompanion\Column\Properties\FilterFramework;

class FilterFrameworkTest extends TestCase
{
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
            'filterFramework' => 'set',
            'filterParams' => [
                'values'        => ['1', '2', '3'],
                'newRowsAction' => 'keep',
                'apply'         => true,
            ]
        ];

        // Act
        $filter = new FilterFramework($type, $params);

        // Assert
        $this->assertEquals($filter->toArray(), $expectedFilterArray);
    }
}
