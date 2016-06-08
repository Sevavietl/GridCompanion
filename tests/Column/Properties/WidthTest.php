<?php

use Sevavietl\GridCompanion\Column\Properties\Width;

class WidthTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        // Arrange
        $value = 108;

        // Act
        $width = new Width($value);

        // Assert
        $this->assertEquals(108, $width->getValue());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBooleanValidationException()
    {
        // Arrange
        $booleanValue = false;

        // Act
        $width = new Width($booleanValue);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testStringValidationException()
    {
        // Arrange
        $stringValue = '18';

        // Act
        $width = new Width($stringValue);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFloatValidationException()
    {
        // Arrange
        $floatValue = 1.8;

        // Act
        $width = new Width($floatValue);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testArrayValidationException()
    {
        // Arrange
        $arrayValue = [1, 8];

        // Act
        $width = new Width($arrayValue);
    }

    public function testToArray()
    {
        // Arrange
        $value = 120;
        $width = new Width($value);
        $expectedArrayProperty = [
            'width' => 120
        ];

        // Act
        $arrayProperty = $width->toArray();

        // Assert
        $this->assertEquals($arrayProperty, $expectedArrayProperty);
    }
}
