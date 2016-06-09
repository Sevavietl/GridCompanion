<?php

use Sevavietl\GridCompanion\Column\PropertiesStorage;

use Sevavietl\GridCompanion\Column\Properties\SimpleProperty;
use Sevavietl\GridCompanion\Column\Properties\Field;
use Sevavietl\GridCompanion\Column\Properties\Filter;

class PropertiesStorageTest extends PHPUnit_Framework_TestCase
{
    protected $storage;

    protected function setUp()
    {
        $this->storage = new PropertiesStorage();
    }

    protected function tearDown()
    {
        unset($this->storage);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotAttachNotProperty()
    {
        // Arrange
        $object = new StdClass;

        // Act
        $this->storage->attach($object);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotPassDataAlongProperty()
    {
        // Arrange
        $property = new SimpleProperty('headerName', 'First header name');

        // Act
        $this->storage->attach($property, 'some data');
    }

    /**
     * @expectedException \DomainException
     */
    public function testCannotAttachTwoSameProperties()
    {
        // Arrange
        $headerName = new SimpleProperty('headerName', 'Header name');

        // Act
        $this->storage->attach($headerName);
        $this->storage->attach($headerName);
    }

    /**
     * @expectedException \DomainException
     */
    public function testCannotAttachTwoPropertiesOfTheSameClass()
    {
        // Arrange
        $headerName1 = new SimpleProperty('headerName', 'First header name');
        $headerName2 = new SimpleProperty('headerName', 'Second header name');
        $field = new Field('Alias', 'column');

        // Act
        $this->storage->attach($headerName1);
        $this->storage->attach($field);
        $this->storage->attach($headerName2);
    }

    public function testContainsForTwoSameProperiesThatAreDifferentObjects()
    {
        // Arrange
        $headerName1 = new SimpleProperty('headerName', 'Header name');
        $headerName2 = new SimpleProperty('headerName', 'Header name');

        // Act
        $this->storage->attach($headerName1);

        // Assert
        $this->assertTrue($this->storage->contains($headerName2));
    }
}
