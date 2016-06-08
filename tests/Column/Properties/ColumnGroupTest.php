<?php

use Sevavietl\GridCompanion\Column\ColumnGroup;
use Sevavietl\GridCompanion\Column\Column;

use Sevavietl\GridCompanion\Column\Properties\Field;
use Sevavietl\GridCompanion\Column\Properties\HeaderName;
use Sevavietl\GridCompanion\Column\Properties\Width;

class ColumnGroupTest extends PHPUnit_Framework_TestCase
{
    public function testAddColumn()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';
        $column = new Column($model, $alias, $columnName);

        $expectedColumns = new SplQueue;
        $expectedColumns->enqueue($column);

        // Act
        $columnGroup = new ColumnGroup;
        $columnGroup->addColumn($column);

        // Assert
        $this->assertEquals(
            $expectedColumns,
            $columnGroup->getColumns()
        );
    }

    public function testToArray()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';
        $column = new Column($model, $alias, $columnName);

        $property1 = new HeaderName('Column Group');
        $property2 = new Width(120);

        $columnGroup = new ColumnGroup;
        $columnGroup
            ->addProperty($property1)
            ->addProperty($property2)
            ->addColumn($column);

        $expectedArray = [
            'headerName' => 'Column Group',
            'width' => 120,
            'children' => [
                ['field' => 'alias_column']
            ]
        ];

        // Act
        $actualArray = $columnGroup->toArray();

        // Assert
        $this->assertEquals($expectedArray, $actualArray);
    }

    public function testIsColumnGroup()
    {
        // Arrange
        $columnGroup = new ColumnGroup;

        // Act & Assert
        $this->assertTrue($columnGroup->isColumnGroup());
    }
}
