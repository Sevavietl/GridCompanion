<?php

use Sevavietl\GridCompanion\Column\Properties\HeaderName;

class HeaderNameTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        // Arrange
        $value = 'Header Name';

        // Act
        $headerName = new HeaderName($value);

        // Assert
        $this->assertEquals('Header Name', $headerName->getValue());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBooleanValidationException()
    {
        // Arrange
        $booleanValue = false;

        // Act
        $headerName = new HeaderName($booleanValue);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIntegerValidationException()
    {
        // Arrange
        $integerValue = 18;

        // Act
        $headerName = new HeaderName($integerValue);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFloatValidationException()
    {
        // Arrange
        $floatValue = 1.8;

        // Act
        $headerName = new HeaderName($floatValue);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testArrayValidationException()
    {
        // Arrange
        $arrayValue = [1, 8];

        // Act
        $headerName = new HeaderName($arrayValue);
    }

    public function testToArray()
    {
        // Arrange
        $value = 'Header Name';
        $headerName = new HeaderName($value);
        $expectedArrayProperty = [
            'headerName' => 'Header Name'
        ];

        // Act
        $arrayProperty = $headerName->toArray();

        // Assert
        $this->assertEquals($arrayProperty, $expectedArrayProperty);
    }
}
