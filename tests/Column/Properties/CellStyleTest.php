<?php

use Sevavietl\GridCompanion\Column\Properties\CellStyle;

class CellStyleTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        // Arrange
        $value = 'function () {}';

        // Act
        $cellStyle = new CellStyle($value);

        // Assert
        $this->assertEquals($value, $cellStyle->getValue());
    }

    public function testToArray()
    {
        // Arrange
        $value = 'function () {}';
        $cellStyle = new CellStyle($value);
        $expectedArrayProperty = [
            'cellStyle' => 'function () {}'
        ];

        // Act
        $arrayProperty = $cellStyle->toArray();

        // Assert
        $this->assertEquals($arrayProperty, $expectedArrayProperty);
    }
}
