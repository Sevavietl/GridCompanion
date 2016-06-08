<?php

use Sevavietl\GridCompanion\ColumnDefinitions;
use Sevavietl\GridCompanion\Column\Column;

use Sevavietl\GridCompanion\Column\Properties\Field;
use Sevavietl\GridCompanion\Column\Properties\HeaderName;
use Sevavietl\GridCompanion\Column\Properties\Width;

class ColumnDefinitionsTest extends TestCase
{
    public function testInstantiation()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';

        // Act
        $columnDefinitions = new ColumnDefinitions($model, $alias);

        // Assert
        $this->assertAttributeEquals(
            $model,
            'model',
            $columnDefinitions
        );

        $this->assertAttributeEquals(
            $alias,
            'alias',
            $columnDefinitions
        );

        $this->assertAttributeEquals(
            (new SplQueue),
            'definitions',
            $columnDefinitions
        );
    }

    public function testAdd()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';
        $column = new Column($model, $alias, $columnName);
        $definitionsQueue = new SplQueue;
        $definitionsQueue->enqueue($column);

        // Act
        $columnDefinitions = new ColumnDefinitions($model, $alias);
        $columnDefinitions->add($column);

        // Assert
        $this->assertEquals(
            $definitionsQueue,
            $columnDefinitions->getDefinitions()
        );
    }

    public function testToArray()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';
        $column = new Column($model, $alias, $columnName);
        $columnDefinitions = new ColumnDefinitions($model, $alias);
        $columnDefinitions->add($column);

        $expectedArray = [
            [
                'field' => 'alias_column'
            ]
        ];

        // Act
        $actualArray = $columnDefinitions->toArray();

        // Assert
        $this->assertEquals($expectedArray, $actualArray);
    }

    public function testAddToHash()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';
        $columnAlias = 'alias_column';
        $columnDefinitions = new ColumnDefinitions($model, $alias);

        $hash = [
            $columnAlias => [
                'model' => $model,
                'alias' => $alias,
                'columnName' => $columnName
            ]
        ];

        // Act
        $columnDefinitions->addToHash($hash);

        // Arrange
        $this->assertAttributeEquals(
            $hash,
            'hash',
            $columnDefinitions
        );
    }

    public function testGetHash()
    {
        // Arrange
        $model = 'Model';
        $alias = 'Alias';
        $columnName = 'column';
        $columnAlias = 'alias_column';
        $columnDefinitions = new ColumnDefinitions($model, $alias);

        $hash = [
            $columnAlias => [
                'model' => $model,
                'alias' => $alias,
                'columnName' => $columnName
            ]
        ];

        // Act
        $columnDefinitions->addToHash($hash);

        // Arrange
        $this->assertEquals($hash, $columnDefinitions->getHash());
    }
}
