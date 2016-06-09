<?php

class SimplePropertyTest extends TestCase
{
    protected $simpleProperty;

    protected function setUp()
    {
        $mock = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Column\Properties\SimpleProperty'
        )
        ->setMethods(null)
        ->disableOriginalConstructor()
        ->getMock();

        $this->simpleProperty = $mock;
    }

    public function testInstantiation()
    {
        // Arrange
        $name = 'width';
        $value = 120;

        // Act
        $this->simpleProperty->__construct($name, $value);

        // Assert
        $this->AssertAttributeEquals(
            $name,
            'name',
            $this->simpleProperty
        );
        $this->AssertAttributeEquals(
            $value,
            'value',
            $this->simpleProperty
        );
    }

    public function testToArray()
    {
        // Arrange
        $name = 'width';
        $value = 120;

        $this->setAttribute($this->simpleProperty, 'name', $name);
        $this->setAttribute($this->simpleProperty, 'value', $value);

        $expectedArray = [
            'width' => 120
        ];

        // Act
        $actualArray = $this->simpleProperty->toArray();

        // Assert
        $this->AssertEquals($expectedArray, $actualArray);
    }

}
