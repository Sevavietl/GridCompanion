<?php

use Sevavietl\GridCompanion\Column\Properties\CellRenderer;

class CellRendererTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        // Arrange
        $value = 'function () {}';

        // Act
        $cellRenderer = new CellRenderer($value);

        // Assert
        $this->assertEquals($value, $cellRenderer->getValue());
    }

    public function testToArray()
    {
        // Arrange
        $value = 'function () {}';
        $cellRenderer = new CellRenderer($value);
        $expectedArrayProperty = [
            'cellRenderer' => 'function () {}'
        ];

        // Act
        $arrayProperty = $cellRenderer->toArray();

        // Assert
        $this->assertEquals($arrayProperty, $expectedArrayProperty);
    }
}
