<?php

use Sevavietl\GridCompanion\Column\Properties\ColId;

class ColIdTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        // Arrange
        $value = 'colId';

        // Act
        $colId = new ColId($value);

        // Assert
        $this->assertEquals($value, $colId->getValue());
    }

    public function testToArray()
    {
        // Arrange
        $value = 'colId';
        $colId = new ColId($value);
        $expectedArrayProperty = [
            'colId' => 'colId'
        ];

        // Act
        $arrayProperty = $colId->toArray();

        // Assert
        $this->assertEquals($arrayProperty, $expectedArrayProperty);
    }
}
