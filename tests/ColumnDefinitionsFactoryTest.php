<?php

use Sevavietl\GridCompanion\ColumnDefinitionsFactory;
use Sevavietl\GridCompanion\ColumnDefinitions;
use Sevavietl\GridCompanion\Column\Column;
use Sevavietl\GridCompanion\Column\ColumnGroup;

use Sevavietl\GridCompanion\Contracts\ColumnDefinition;
use Sevavietl\GridCompanion\Column\Properties\Property;

use Sevavietl\GridCompanion\Column\Properties\Field;
use Sevavietl\GridCompanion\Column\Properties\Filter;
use Sevavietl\GridCompanion\Column\Properties\SimpleProperty;

class ColumnDefinitionsFactoryTest extends TestCase
{
    protected $builder;

    protected function setUp()
    {
        $this->builder = new ColumnDefinitionsFactory;
    }

    protected function tearDown()
    {
        unset($this->builder);
    }

    public function testSetEnabledColumnIds()
    {
        // Arrange
        $enabledColumnIds = [1, 2, 3];

        // Act
        $this->builder->setEnabledColumnIds($enabledColumnIds);

        // Assert
        $this->assertAttributeEquals(
            $enabledColumnIds,
            'enabledColumnIds',
            $this->builder
        );
    }

    public function testSetDisabledColumnIds()
    {
        // Arrange
        $disabledColumnIds = [1, 2, 3];

        // Act
        $this->builder->setDisabledColumnIds($disabledColumnIds);

        // Assert
        $this->assertAttributeEquals(
            $disabledColumnIds,
            'disabledColumnIds',
            $this->builder
        );
    }

    public function testBuildProperty()
    {
        // Arrange
        $expectedProperty1 = new SimpleProperty('headerName', 'Header Name');
        $expectedProperty2 = new SimpleProperty('width', 120);
        $expectedProperty3 = new Filter('text');
        $expectedProperty4 = new Filter('text', []);
        $expectedProperty5 = new Filter('text', [
            'newRowsAction' => 'keep',
            'apply'         => true,
        ]);

        // Act
        $actualProperty1 = $this->invokeMethod(
            $this->builder,
            'buildProperty',
            ['headerName', 'Header Name']
        );
        $actualProperty2 = $this->invokeMethod(
            $this->builder,
            'buildProperty',
            ['width', 120]
        );
        $actualProperty3 = $this->invokeMethod(
            $this->builder,
            'buildProperty',
            ['filter', ['type' => 'text']]
        );
        $actualProperty4 = $this->invokeMethod(
            $this->builder,
            'buildProperty',
            [
                'filter',
                [
                    'type' => 'text',
                    'params' => []
                ]
            ]
        );
        $actualProperty5 = $this->invokeMethod(
            $this->builder,
            'buildProperty',
            [
                'filter',
                [
                    'type' => 'text',
                    'params' => [
                        'newRowsAction' => 'keep',
                        'apply'         => true,
                    ]
                ]
            ]
        );

        // Assert
        $this->assertEquals(
            $expectedProperty1,
            $actualProperty1
        );
        $this->assertEquals(
            $expectedProperty2,
            $actualProperty2
        );
        $this->assertEquals(
            $expectedProperty3,
            $actualProperty3
        );
        $this->assertEquals(
            $expectedProperty4,
            $actualProperty4
        );
        $this->assertEquals(
            $expectedProperty5,
            $actualProperty5
        );
    }

    public function testBuildColumn()
    {
        // Arrange
        $columnDefinition = [
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $property1 = new SimpleProperty('headerName', 'Column');
        $property2 = new SimpleProperty('width', 120);
        $property3 = new Field(
            $columnDefinition['alias'],
            $columnDefinition['field']
        );

        $expectedColumn = new Column(
            $columnDefinition['model'],
            $columnDefinition['alias'],
            $columnDefinition['field']
        );
        $expectedColumn->addProperty($property1)->addProperty($property2);

        // Act
        $actualColumn = $this->invokeMethod(
            $this->builder,
            'buildColumn',
            [$columnDefinition]
        );

        // Assert
        $this->assertEquals(
            $expectedColumn->getModel(),
            $actualColumn->getModel()
        );
        $this->assertEquals(
            $expectedColumn->getAlias(),
            $actualColumn->getAlias()
        );
        $this->assertEquals(
            $expectedColumn->getColumnName(),
            $actualColumn->getColumnName()
        );

        $this->columnHasProperty($actualColumn, $property1);
        $this->columnHasProperty($actualColumn, $property2);
        $this->columnHasProperty($actualColumn, $property3);
    }

    public function testBuildColumnWithGlobalModelAndAlias()
    {
        // Arrange
        $columnDefinition = [
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $this->setAttribute($this->builder, 'model', 'Model');
        $this->setAttribute($this->builder, 'alias', 'Alias');

        $property1 = new SimpleProperty('headerName', 'Column');
        $property2 = new SimpleProperty('width', 120);
        $property3 = new Field(
            $columnDefinition['alias'],
            $columnDefinition['field']
        );

        $expectedColumn = new Column(
            $columnDefinition['model'],
            $columnDefinition['alias'],
            $columnDefinition['field']
        );
        $expectedColumn->addProperty($property1)->addProperty($property2);

        unset($columnDefinition['model']);
        unset($columnDefinition['alias']);

        // Act
        $actualColumn = $this->invokeMethod(
            $this->builder,
            'buildColumn',
            [$columnDefinition]
        );

        // Assert
        $this->assertEquals(
            $expectedColumn->getModel(),
            $actualColumn->getModel()
        );
        $this->assertEquals(
            $expectedColumn->getAlias(),
            $actualColumn->getAlias()
        );
        $this->assertEquals(
            $expectedColumn->getColumnName(),
            $actualColumn->getColumnName()
        );

        $this->columnHasProperty($actualColumn, $property1);
        $this->columnHasProperty($actualColumn, $property2);
        $this->columnHasProperty($actualColumn, $property3);
    }

    /**
     * @expectedException \DomainException
     */
    public function testBuildColumnThrowsExceptionWhenNoField()
    {
        // Arrange
        $columnDefinition = [
            'model'      => 'Model',
            'alias'      => 'Alias',
            'headerName' => 'Column',
            'width'      => 120
        ];

        // Act
        $actualColumn = $this->invokeMethod(
            $this->builder,
            'buildColumn',
            [$columnDefinition]
        );
    }

    public function testBuildEnabledColumn()
    {
        // Arrange
        $columnDefinition = [
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $enabledColumnIds = ['alias_column'];
        $this->setAttribute(
            $this->builder,
            'enabledColumnIds',
            $enabledColumnIds
        );

        // Act
        $actualColumn = $this->invokeMethod(
            $this->builder,
            'buildColumn',
            [$columnDefinition]
        );

        $this->assertNotNull($actualColumn);
    }

    public function testBuildDisabledColumn()
    {
        // Arrange
        $columnDefinition = [
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $disabledColumnIds = ['alias_column'];
        $this->setAttribute(
            $this->builder,
            'disabledColumnIds',
            $disabledColumnIds
        );

        // Act
        $actualColumn = $this->invokeMethod(
            $this->builder,
            'buildColumn',
            [$columnDefinition]
        );

        $this->assertNull($actualColumn);
    }

    public function testIsColumnEnabled()
    {
        // Arrange
        $columnDefinition1 = [
            'id'         => 1,
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $columnDefinition2 = [
            'id'         => 2,
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $enabledColumnIds = [1];

        $this->setAttribute(
            $this->builder,
            'enabledColumnIds',
            $enabledColumnIds
        );

        // Act & Assert
        $this->columnIsEnabled($columnDefinition1);
        $this->columnIsNotEnabled($columnDefinition2);
    }

    public function testIsColumnEnabledWhenEmptyEnabledColumnIds()
    {
        // Arrange
        $columnDefinition = [
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        // Act & Assert
        $this->columnIsEnabled($columnDefinition);
    }

    public function testIsColumnEnabledWhenNoIdSpecified()
    {
        // Arrange
        $columnDefinition1 = [
            'model'      => 'Model',
            'alias'      => 'Alias1',
            'field'      => 'column1',
            'headerName' => 'Column1',
            'width'      => 120
        ];

        $columnDefinition2 = [
            'model'      => 'Model',
            'alias'      => 'Alias2',
            'field'      => 'column2',
            'headerName' => 'Column2',
            'width'      => 120
        ];

        $enabledColumnIds = ['alias1_column1'];

        $this->setAttribute(
            $this->builder,
            'enabledColumnIds',
            $enabledColumnIds
        );

        // Act & Assert
        $this->columnIsEnabled($columnDefinition1);
        $this->columnIsNotEnabled($columnDefinition2);
    }

    protected function columnIsEnabled(array $columnDefinition)
    {
        $this->assertTrue(
            $this->invokeMethod(
                $this->builder,
                'isColumnEnabled',
                [$columnDefinition]
            )
        );
    }

    protected function columnIsNotEnabled(array $columnDefinition)
    {
        $this->assertFalse(
            $this->invokeMethod(
                $this->builder,
                'isColumnEnabled',
                [$columnDefinition]
            )
        );
    }

    public function testIsColumnDisabled()
    {
        // Arrange
        $columnDefinition1 = [
            'id'         => 1,
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $columnDefinition2 = [
            'id'         => 2,
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        $disabledColumnIds = [1];

        $this->setAttribute(
            $this->builder,
            'disabledColumnIds',
            $disabledColumnIds
        );

        // Act & Assert
        $this->columnIsDisabled($columnDefinition1);
        $this->columnIsNotDisabled($columnDefinition2);
    }

    public function testIsColumnDisabledWhenEmptyDisabledColumnIds()
    {
        // Arrange
        $columnDefinition = [
            'model'      => 'Model',
            'alias'      => 'Alias',
            'field'      => 'column',
            'headerName' => 'Column',
            'width'      => 120
        ];

        // Act & Assert
        $this->columnIsNotDisabled($columnDefinition);
    }

    public function testIsColumnDisabledWhenNoIdSpecified()
    {
        // Arrange
        $columnDefinition1 = [
            'model'      => 'Model',
            'alias'      => 'Alias1',
            'field'      => 'column1',
            'headerName' => 'Column1',
            'width'      => 120
        ];

        $columnDefinition2 = [
            'model'      => 'Model',
            'alias'      => 'Alias2',
            'field'      => 'column2',
            'headerName' => 'Column2',
            'width'      => 120
        ];

        $disabledColumnIds = ['alias1_column1'];

        $this->setAttribute(
            $this->builder,
            'disabledColumnIds',
            $disabledColumnIds
        );

        // Act & Assert
        $this->columnIsDisabled($columnDefinition1);
        $this->columnIsNotDisabled($columnDefinition2);
    }

    protected function columnIsDisabled(array $columnDefinition)
    {
        $this->assertTrue(
            $this->invokeMethod(
                $this->builder,
                'isColumnDisabled',
                [$columnDefinition]
            )
        );
    }

    protected function columnIsNotDisabled(array $columnDefinition)
    {
        $this->assertFalse(
            $this->invokeMethod(
                $this->builder,
                'isColumnDisabled',
                [$columnDefinition]
            )
        );
    }

    public function testHashCurrentColumn()
    {
        // Arrange
        $column = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Column\Column'
        )
        ->disableOriginalConstructor()
        ->setMethods(['getColumnAlias', 'getModel', 'getAlias', 'getColumnName'])
        ->getMock();

        $column->expects($this->once())
            ->method('getColumnAlias')
            ->willReturn('alias_column');
        $column->expects($this->once())
            ->method('getModel')
            ->willReturn('Model');
        $column->expects($this->once())
            ->method('getAlias')
            ->willReturn('Alias');
        $column->expects($this->once())
            ->method('getColumnName')
            ->willReturn('column');

        $this->setAttribute($this->builder, 'currentColumn', $column);

        $expectedHash = [
            'alias_column' => [
                'model'  => 'Model',
                'alias'  => 'Alias',
                'column' => 'column'
            ]
        ];

        // Act
        $this->invokeMethod($this->builder, 'hashCurrentColumn');

        // Assert
        $this->assertAttributeEquals(
            $expectedHash,
            'currentHash',
            $this->builder
        );
    }

    public function testBuildColumnGroupOneColumn()
    {
        // Arrange
        $columnGroupDefinition = [
            'headerName' => 'Column Group',
            'width'      => 120,
            'children'   => [
                [
                    'model'      => 'Model',
                    'alias'      => 'Alias',
                    'field'      => 'column',
                    'headerName' => 'Column',
                    'width'      => 120
                ]
            ]
        ];

        $columnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['headerName']
        );
        $columnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['width']
        );

        $columnProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['headerName']
        );
        $columnProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['width']
        );
        $columnProperty3 = new Field(
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['field']
        );

        $expectedColumn = new Column(
            $columnGroupDefinition['children'][0]['model'],
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['field']
        );
        $expectedColumn
            ->addProperty($columnProperty1)
            ->addProperty($columnProperty2);
        $expectedColumns = new SplQueue;
        $expectedColumns->enqueue($expectedColumn);

        // Act
        $actualColumnGroup = $this->invokeMethod(
            $this->builder,
            'buildColumnGroup',
            [$columnGroupDefinition]
        );

        // Assert
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty1);
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty2);

        $this->assertEquals(
            $expectedColumns,
            $actualColumnGroup->getColumns()
        );
    }

    public function testBuildColumnGroupMultipleColumns()
    {
        // Arrange
        $columnGroupDefinition = [
            'headerName' => 'Column Group',
            'width'      => 120,
            'children'   => [
                [
                    'model'      => 'Model1',
                    'alias'      => 'Alias1',
                    'field'      => 'column1',
                    'headerName' => 'Column1',
                    'width'      => 120
                ],
                [
                    'model'      => 'Model2',
                    'alias'      => 'Alias2',
                    'field'      => 'column2',
                    'headerName' => 'Column2',
                    'width'      => 120
                ]
            ]
        ];

        $columnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['headerName']
        );
        $columnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['width']
        );

        $columnProperty11 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['headerName']
        );
        $columnProperty12 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][1]['headerName']
        );

        $columnProperty21 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['width']
        );
        $columnProperty22 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][1]['width']
        );

        $columnProperty31 = new Field(
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][1]['field']
        );
        $columnProperty32 = new Field(
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][1]['field']
        );

        $expectedColumn1 = new Column(
            $columnGroupDefinition['children'][0]['model'],
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['field']
        );
        $expectedColumn1
            ->addProperty($columnProperty11)
            ->addProperty($columnProperty21);

        $expectedColumn2 = new Column(
            $columnGroupDefinition['children'][1]['model'],
            $columnGroupDefinition['children'][1]['alias'],
            $columnGroupDefinition['children'][1]['field']
        );
        $expectedColumn2
            ->addProperty($columnProperty12)
            ->addProperty($columnProperty22);

        $expectedColumns = new SplQueue;
        $expectedColumns->enqueue($expectedColumn1);
        $expectedColumns->enqueue($expectedColumn2);

        // Act
        $actualColumnGroup = $this->invokeMethod(
            $this->builder,
            'buildColumnGroup',
            [$columnGroupDefinition]
        );

        // Assert
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty1);
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty2);

        $this->assertEquals(
            $expectedColumns,
            $actualColumnGroup->getColumns()
        );
    }

    public function testBuildColumnGroupMultipleColumnsWhenSomeEnabled()
    {
        // Arrange
        $columnGroupDefinition = [
            'headerName' => 'Column Group',
            'width'      => 120,
            'children'   => [
                [
                    'model'      => 'Model1',
                    'alias'      => 'Alias1',
                    'field'      => 'column1',
                    'headerName' => 'Column1',
                    'width'      => 120
                ],
                [
                    'model'      => 'Model2',
                    'alias'      => 'Alias2',
                    'field'      => 'column2',
                    'headerName' => 'Column2',
                    'width'      => 120
                ]
            ]
        ];

        $columnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['headerName']
        );
        $columnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['width']
        );

        $columnProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['headerName']
        );

        $columnProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['width']
        );

        $columnProperty3 = new Field(
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][1]['field']
        );

        $expectedColumn1 = new Column(
            $columnGroupDefinition['children'][0]['model'],
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['field']
        );
        $expectedColumn1
            ->addProperty($columnProperty1)
            ->addProperty($columnProperty2);

        $expectedColumns = new SplQueue;
        $expectedColumns->enqueue($expectedColumn1);

        $enabledColumnIds = ['alias1_column1'];

        $this->setAttribute(
            $this->builder,
            'enabledColumnIds',
            $enabledColumnIds
        );

        // Act
        $actualColumnGroup = $this->invokeMethod(
            $this->builder,
            'buildColumnGroup',
            [$columnGroupDefinition]
        );

        // Assert
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty1);
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty2);

        $this->assertEquals(
            $expectedColumns,
            $actualColumnGroup->getColumns()
        );
    }

    public function testBuildColumnGroupMultipleColumnsWhenSomeDisabled()
    {
        // Arrange
        $columnGroupDefinition = [
            'headerName' => 'Column Group',
            'width'      => 120,
            'children'   => [
                [
                    'model'      => 'Model1',
                    'alias'      => 'Alias1',
                    'field'      => 'column1',
                    'headerName' => 'Column1',
                    'width'      => 120
                ],
                [
                    'model'      => 'Model2',
                    'alias'      => 'Alias2',
                    'field'      => 'column2',
                    'headerName' => 'Column2',
                    'width'      => 120
                ]
            ]
        ];

        $columnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['headerName']
        );
        $columnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['width']
        );

        $columnProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['headerName']
        );

        $columnProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['width']
        );

        $columnProperty3 = new Field(
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][1]['field']
        );

        $expectedColumn1 = new Column(
            $columnGroupDefinition['children'][0]['model'],
            $columnGroupDefinition['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['field']
        );
        $expectedColumn1
            ->addProperty($columnProperty1)
            ->addProperty($columnProperty2);

        $expectedColumns = new SplQueue;
        $expectedColumns->enqueue($expectedColumn1);

        $disabledColumnIds = ['alias2_column2'];

        $this->setAttribute(
            $this->builder,
            'disabledColumnIds',
            $disabledColumnIds
        );

        // Act
        $actualColumnGroup = $this->invokeMethod(
            $this->builder,
            'buildColumnGroup',
            [$columnGroupDefinition]
        );

        // Assert
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty1);
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty2);

        $this->assertEquals(
            $expectedColumns,
            $actualColumnGroup->getColumns()
        );
    }

    public function testBuildNestedColumnGroups()
    {
        // Arrange
        $columnGroupDefinition = [
            'headerName' => 'Column Group',
            'width'      => 120,
            'children'   => [
                [
                    'headerName' => 'Nested Column Group',
                    'width'      => 120,
                    'children'   => [
                        [
                            'model'      => 'Model',
                            'alias'      => 'Alias',
                            'field'      => 'column',
                            'headerName' => 'Column',
                            'width'      => 120
                        ]
                    ]
                ]
            ]
        ];

        // Main column group properties.
        $columnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['headerName']
        );
        $columnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['width']
        );

        // Nested column group properties.
        $nestedColumnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['headerName']
        );
        $nestedColumnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['width']
        );

        // Properties for the column inside the nested column group.
        $columnProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['children'][0]['headerName']
        );
        $columnProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['children'][0]['width']
        );
        $columnProperty3 = new Field(
            $columnGroupDefinition['children'][0]['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['children'][0]['field']
        );

        $expectedColumn = new Column(
            $columnGroupDefinition['children'][0]['children'][0]['model'],
            $columnGroupDefinition['children'][0]['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['children'][0]['field']
        );
        $expectedColumn
            ->addProperty($columnProperty1)
            ->addProperty($columnProperty2);

        $nestedColumnGroup = new ColumnGroup;
        $nestedColumnGroup->addColumn($expectedColumn);

        $expectedColumns = new SplQueue;
        $expectedColumns->enqueue($nestedColumnGroup);

        // Act
        $actualColumnGroup = $this->invokeMethod(
            $this->builder,
            'buildColumnGroup',
            [$columnGroupDefinition]
        );

        // Assert
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty1);
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty2);

        $this->assertEquals(
            $expectedColumns,
            $actualColumnGroup->getColumns()
        );
    }

    public function testBuildNestedColumnGroupsWithColumnOnTheSameLevel()
    {
        // Arrange
        $columnGroupDefinition = [
            'headerName' => 'Column Group',
            'width'      => 240,
            'children'   => [
                [
                    'headerName' => 'Nested Column Group',
                    'width'      => 120,
                    'children'   => [
                        [
                            'model'      => 'Model2',
                            'alias'      => 'Alias2',
                            'field'      => 'column2',
                            'headerName' => 'Column2',
                            'width'      => 120
                        ]
                    ]
                ],
                [
                    'model'      => 'Model1',
                    'alias'      => 'Alias1',
                    'field'      => 'column1',
                    'headerName' => 'Column1',
                    'width'      => 120
                ]

            ]
        ];

        // Main column group properties.
        $columnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['headerName']
        );
        $columnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['width']
        );

        // Nested column group properties.
        $nestedColumnGroupProperty1 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['headerName']
        );
        $nestedColumnGroupProperty2 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['width']
        );

        // Properties for the column inside the nested column group.
        $columnProperty12 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][0]['children'][0]['headerName']
        );
        $columnProperty22 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][0]['children'][0]['width']
        );
        $columnProperty32 = new Field(
            $columnGroupDefinition['children'][0]['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['children'][0]['field']
        );

        // Column inside the nested column group.
        $expectedColumn2 = new Column(
            $columnGroupDefinition['children'][0]['children'][0]['model'],
            $columnGroupDefinition['children'][0]['children'][0]['alias'],
            $columnGroupDefinition['children'][0]['children'][0]['field']
        );
        $expectedColumn2
            ->addProperty($columnProperty12)
            ->addProperty($columnProperty22);

        $nestedColumnGroup = new ColumnGroup;
        $nestedColumnGroup->addColumn($expectedColumn2);

        // Properties for the column inside the main column group.
        $columnProperty11 = new SimpleProperty(
            'headerName',
            $columnGroupDefinition['children'][1]['headerName']
        );
        $columnProperty21 = new SimpleProperty(
            'width',
            $columnGroupDefinition['children'][1]['width']
        );
        $columnProperty31 = new Field(
            $columnGroupDefinition['children'][1]['alias'],
            $columnGroupDefinition['children'][1]['field']
        );

        // Column inside the main column group.
        $expectedColumn1 = new Column(
            $columnGroupDefinition['children'][1]['model'],
            $columnGroupDefinition['children'][1]['alias'],
            $columnGroupDefinition['children'][1]['field']
        );
        $expectedColumn1
            ->addProperty($columnProperty11)
            ->addProperty($columnProperty21);

        $expectedColumns = new SplQueue;
        $expectedColumns->enqueue($nestedColumnGroup);
        $expectedColumns->enqueue($expectedColumn1);

        // Act
        $actualColumnGroup = $this->invokeMethod(
            $this->builder,
            'buildColumnGroup',
            [$columnGroupDefinition]
        );

        // Assert
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty1);
        $this->columnHasProperty($actualColumnGroup, $columnGroupProperty2);

        $this->assertEquals(
            $expectedColumns,
            $actualColumnGroup->getColumns()
        );
    }

    protected function columnHasProperty(
        ColumnDefinition $column,
        Property $property
    ) {
        $this->assertTrue(
            $column->getProperties()->contains($property)
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testBuildColumnGroupThrowsExceptionWhenNoChildren()
    {
        // Arrange
        $columnGroupDefinition = [
            'headerName' => 'Column',
            'width'      => 120
        ];

        // Act
        $actualColumnGroup = $this->invokeMethod(
            $this->builder,
            'buildColumnGroup',
            [$columnGroupDefinition]
        );
    }

    public function testSeparateColumnDefinitionsSchema()
    {
        // Arrange
        $schema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field'      => 'column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'column2',
                'width'      => 120
            ]
        ];

        $expectedColumnDefinitionsSchema = [
            [
                'headerName' => 'Column 1',
                'field'      => 'column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'column2',
                'width'      => 120
            ]
        ];

        $this->setAttribute(
            $this->builder,
            'schema',
            $schema
        );

        // Act
        $actualColumnDefinitionsSchema = $this->invokeMethod(
            $this->builder,
            'separateColumnDefinitionsSchema'
        );

        // Assert
        $this->assertEquals(
            $expectedColumnDefinitionsSchema,
            $actualColumnDefinitionsSchema
        );
    }

    public function testBuild()
    {
        // Arrange
        $schema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field'      => 'column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'column2',
                'width'      => 120
            ]
        ];

        $columnProperty11 = new SimpleProperty(
            'headerName',
            $schema[0]['headerName']
        );
        $columnProperty21 = new SimpleProperty(
            'width',
            $schema[0]['width']
        );
        $column1 = (new Column(
                $schema['model'],
                $schema['alias'],
                $schema[0]['field']
            ))
            ->addProperty($columnProperty11)
            ->addProperty($columnProperty21);

        $columnProperty12 = new SimpleProperty(
            'headerName',
            $schema[1]['headerName']
        );
        $columnProperty22 = new SimpleProperty(
            'width',
            $schema[1]['width']
        );
        $column2 = (new Column(
                $schema['model'],
                $schema['alias'],
                $schema[1]['field']
            ))
            ->addProperty($columnProperty12)
            ->addProperty($columnProperty22);

        $expectedDefinitions = new SplQueue;
        $expectedDefinitions->enqueue($column1);
        $expectedDefinitions->enqueue($column2);

        $expectedDefinitionsArray = [
            [
                'headerName' => 'Column 1',
                'field'      => 'alias_column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'alias_column2',
                'width'      => 120
            ]
        ];

        // Act
        $columnDefinitions = $this->builder->build($schema);

        // Assert
        $this->assertInstanceOf(
            'Sevavietl\GridCompanion\ColumnDefinitions',
            $columnDefinitions
        );

        $this->assertEquals(
            $expectedDefinitions,
            $columnDefinitions->getDefinitions()
        );

        $this->assertEquals(
            $expectedDefinitionsArray,
            $columnDefinitions->toArray()
        );
    }

    public function testSetUpModelAndAlias()
    {
        // Arrange
        $schema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field'      => 'column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'column2',
                'width'      => 120
            ]
        ];

        $this->setAttribute(
            $this->builder,
            'schema',
            $schema
        );

        // Act
        $this->invokeMethod(
            $this->builder,
            'setUpModelAndAlias'
        );

        // Assert
        $this->assertAttributeEquals(
            'Model',
            'model',
            $this->builder
        );

        $this->assertAttributeEquals(
            'Alias',
            'alias',
            $this->builder
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetUpModelAndAliasThrowsExceptionWithNoModelInSchema()
    {
        // Arrange
        $schema = [
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field'      => 'column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'column2',
                'width'      => 120
            ]
        ];

        $this->setAttribute(
            $this->builder,
            'schema',
            $schema
        );

        // Act
        $this->invokeMethod(
            $this->builder,
            'setUpModelAndAlias'
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetUpModelAndAliasThrowsExceptionWithNoAliasInSchema()
    {
        // Arrange
        $schema = [
            'model' => 'Model',
            [
                'headerName' => 'Column 1',
                'field'      => 'column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'column2',
                'width'      => 120
            ]
        ];

        $this->setAttribute(
            $this->builder,
            'schema',
            $schema
        );

        // Act
        $this->invokeMethod(
            $this->builder,
            'setUpModelAndAlias'
        );
    }

    public function testClearDown()
    {
        // Arrange
        $schema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field'      => 'column1',
                'width'      => 120
            ],
            [
                'headerName' => 'Column 2',
                'field'      => 'column2',
                'width'      => 120
            ]
        ];

        $this->setAttribute(
            $this->builder,
            'schema',
            $schema
        );
        $this->setAttribute(
            $this->builder,
            'model',
            'Model'
        );
        $this->setAttribute(
            $this->builder,
            'alias',
            'Alias'
        );

        // Act
        $this->invokeMethod($this->builder, 'clearDown');

        // Assert
        $this->assertAttributeEquals(
            null,
            'model',
            $this->builder
        );
        $this->assertAttributeEquals(
            null,
            'alias',
            $this->builder
        );
        $this->assertAttributeEquals(
            null,
            'schema',
            $this->builder
        );
    }
}
