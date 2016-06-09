<?php

use Sevavietl\GridCompanion\Column\Column;

use Sevavietl\GridCompanion\Column\Properties\Field;
use Sevavietl\GridCompanion\Column\Properties\SimpleProperty;

use Sevavietl\GridCompanion\Column\PropertiesStorage;

class ColumnTest extends TestCase
{
    protected $column;

    protected function setUp()
    {
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';

        $this->column = new Column($model, $alias, $columnName);
    }

    protected function tearDown()
    {
        unset($this->column);
    }

    public function testInstantiation()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';
        $columnAlias = 'alias_column';
        $field = new Field($alias, $columnName);

        // Act
        $column = new Column($model, $alias, $columnName);
        $properties = $column->getProperties();

        // Assert
        $this->assertEquals($model, $column->getModel());
        $this->assertEquals($alias, $column->getAlias());
        $this->assertEquals($columnName, $column->getColumnName());
        $this->assertEquals($columnAlias, $column->getColumnAlias());

        $this->assertTrue($properties->contains($field));
    }

    public function testAddProperty()
    {
        // Arrange
        $property = new SimpleProperty('headerName', 'Header Name');

        // Act
        $this->column->addProperty($property);
        $properties = $this->column->getProperties();

        // Assert
        $this->assertTrue($properties->contains($property));
    }

    public function testToArray()
    {
        // Arrange
        $property1 = new SimpleProperty('headerName', 'Header Name');
        $property2 = new SimpleProperty('width', 120);
        $expectedArray = [
            'field'      => 'alias_column',
            'headerName' => 'Header Name',
            'width'      => 120
        ];

        // Act
        $this->column
            ->addProperty($property1)
            ->addProperty($property2);

        // Assert
        $this->assertEquals($expectedArray, $this->column->toArray());
    }

    public function testGetModel()
    {
        // Arrange

        // Act
        $model = $this->column->getModel();

        $this->setAttribute($this->column, 'model', null);
        $defaultModel = $this->column->getModel('Default Model');

        // Assert
        $this->assertEquals('Model', $model);
        $this->assertEquals('Default Model', $defaultModel);
    }

    public function testGetAlias()
    {
        // Arrange

        // Act
        $alias = $this->column->getAlias();

        $this->setAttribute($this->column, 'alias', null);
        $defaultAlias = $this->column->getAlias('Default Alias');

        // Assert
        $this->assertEquals('Alias', $alias);
        $this->assertEquals('Default Alias', $defaultAlias);
    }

    public function testIsColumnGroup()
    {
        // Act & Assert
        $this->assertFalse($this->column->isColumnGroup());
    }
}
