<?php

use Sevavietl\GridCompanion\QueryParameters;
use Sevavietl\GridCompanion\RowsInterval;

use Sevavietl\GridCompanion\ColumnDefinitions;
use Sevavietl\GridCompanion\Column\Column;

class QueryParametersTest extends TestCase
{
    protected $columnDefinitions;

    protected function setUp()
    {
        $column1 = $this->buildColumn(
            'Model1',
            'Alias1',
            'column1',
            'alias1_column1'
        );
        $column2 = $this->buildColumn(
            'Model2',
            'Alias2',
            'column2',
            'alias2_column2'
        );

        $this->columnDefinitions = $this->buildColumnDefinitions(
            'Model1',
            'Alias1',
            [$column1, $column2]
        );
    }

    protected function buildColumn($model, $alias, $columnName, $columnAlias)
    {
        $column = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Column\\Column'
        )
        ->setMethods([
            'getModel',
            'getAlias',
            'getColumnName',
            'getColumnAlias',
            'isColumnGroup'
        ])
        ->disableOriginalConstructor()
        ->getMock();

        $column->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue($model));
        $column->expects($this->any())
            ->method('getAlias')
            ->will($this->returnValue($alias));
        $column->expects($this->any())
            ->method('getColumnName')
            ->will($this->returnValue($columnName));
        $column->expects($this->any())
            ->method('getColumnAlias')
            ->will($this->returnValue($columnAlias));
        $column->expects($this->any())
            ->method('isColumnGroup')
            ->will($this->returnValue(false));

        return $column;
    }

    protected function buildColumnGroup($columns = [])
    {
        $definitions = new SplQueue;

        foreach ($columns as $column) {
            $definitions->enqueue($column);
        }

        $columnGroup = $this->getMockBuilder(
            'Sevavietl\GridCompanion\Column\\ColumnGroup'
        )
        ->setMethods(['getColumns', 'isColumnGroup'])
        ->disableOriginalConstructor()
        ->getMock();

        $columnGroup->expects($this->any())
            ->method('getColumns')
            ->will($this->returnValue($definitions));
        $columnGroup->expects($this->any())
            ->method('isColumnGroup')
            ->will($this->returnValue(true));

        return $columnGroup;
    }

    protected function buildColumnDefinitions($model, $alias, $columns = [])
    {
        $definitions = new SplQueue;

        foreach ($columns as $column) {
            $definitions->enqueue($column);
        }

        $columnDefinitions = $this->getMockBuilder(
            'Sevavietl\GridCompanion\ColumnDefinitions'
        )
        ->setMethods(['getDefinitions', 'getModel', 'getAlias'])
        ->disableOriginalConstructor()
        ->getMock();

        $columnDefinitions->expects($this->any())
            ->method('getDefinitions')
            ->will($this->returnValue($definitions));
        $columnDefinitions->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue($model));
        $columnDefinitions->expects($this->any())
            ->method('getAlias')
            ->will($this->returnValue($alias));

        return $columnDefinitions;
    }

    protected function tearDown()
    {
        unset($this->columnDefinitions);
    }

    public function testInstantiation()
    {
        // Act
        $queryParameters = new QueryParameters($this->columnDefinitions);

        // Assert
        $this->assertAttributeEquals(
            $this->columnDefinitions,
            'definitions',
            $queryParameters
        );
    }

    public function testSetFrom()
    {
        // Arrange
        $from = [
                'model' => 'Model1',
                'alias' => 'Alias1'
        ];

        $queryParameters = $this->getMockBuilder(
                'Sevavietl\GridCompanion\QueryParameters'
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->setAttribute(
            $queryParameters,
            'definitions',
            $this->columnDefinitions
        );

        // Act
        $this->invokeMethod(
            $queryParameters,
            'setFrom'
        );

        // Assert
        $this->assertAttributeEquals($from, 'from', $queryParameters);
    }

    public function testAddJoin()
    {
        // Arrange
        $join = [
            [
                'model' => 'Model2',
                'alias' => 'Alias2'
            ]
        ];

        $column2 = $this->buildColumn('Model2', 'Alias2', 'column2', 'alias2_column2');

        $queryParameters = $this->getMockBuilder(
                'Sevavietl\GridCompanion\QueryParameters'
            )
            ->disableOriginalConstructor()
            ->getMock();

        // Act
        $this->invokeMethod(
            $queryParameters,
            'addJoin',
            [$column2]
        );

        // Assert
        $this->assertAttributeEquals($join, 'join', $queryParameters);
    }

    public function testAddSelect()
    {
        // Arrange
        $select = [
            [
                'alias'       => 'Alias1',
                'column'      => 'column1',
                'columnAlias' => 'alias1_column1'
            ]
        ];

        $column1 = $this->buildColumn('Model1', 'Alias1', 'column1', 'alias1_column1');

        $queryParameters = $this->getMockBuilder(
                'Sevavietl\GridCompanion\QueryParameters'
            )
            ->disableOriginalConstructor()
            ->getMock();

        // Act
        $this->invokeMethod(
            $queryParameters,
            'addSelect',
            [$column1]
        );

        // Assert
        $this->assertAttributeEquals($select, 'select', $queryParameters);
    }

    public function testAddSelectDuplicatedColumn()
    {
        // Arrange
        $select = [
            [
                'alias'       => 'Alias1',
                'column'      => 'column1',
                'columnAlias' => 'alias1_column1'
            ]
        ];

        $column1 = $this->buildColumn('Model1', 'Alias1', 'column1', 'alias1_column1');
        $column2 = $this->buildColumn('Model1', 'Alias1', 'column1', 'alias1_column1');

        $queryParameters = $this->getMockBuilder(
                'Sevavietl\GridCompanion\QueryParameters'
            )
            ->disableOriginalConstructor()
            ->getMock();

        // Act
        $this->invokeMethod(
            $queryParameters,
            'addSelect',
            [$column1]
        );
        $this->invokeMethod(
            $queryParameters,
            'addSelect',
            [$column2]
        );

        // Assert
        $this->assertAttributeEquals($select, 'select', $queryParameters);
    }

    public function testSetSelectAndJoin()
    {
        // Arrange
        $select = [
            [
                'alias' => 'Alias1',
                'column' => 'column1',
                'columnAlias' => 'alias1_column1'
            ],
            [
                'alias' => 'Alias2',
                'column' => 'column2',
                'columnAlias' => 'alias2_column2'
            ]
        ];
        $join = [
            [
                'model' => 'Model2',
                'alias' => 'Alias2'
            ]
        ];

        $queryParameters = $this->getMockBuilder(
                'Sevavietl\GridCompanion\QueryParameters'
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->setAttribute(
            $queryParameters,
            'definitions',
            $this->columnDefinitions
        );

        // Act
        $this->invokeMethod(
            $queryParameters,
            'setSelectAndJoin',
            [$this->columnDefinitions->getDefinitions()]
        );

        // Assert
        $this->assertAttributeEquals($select, 'select', $queryParameters);
        $this->assertAttributeEquals($join, 'join', $queryParameters);
    }

    public function testSetSelectAndJoinRecursively()
    {
        // Arrange
        $column1 = $this->buildColumn('Model1', 'Alias1', 'column1', 'alias1_column1');
        $column2 = $this->buildColumn('Model2', 'Alias2', 'column2', 'alias2_column2');

        $columnGroup = $this->buildColumnGroup([$column2]);

        $columnDefinitions = $this->buildColumnDefinitions(
            'Model1',
            'Alias1',
            [$column1, $columnGroup]
        );

        $select = [
            [
                'alias' => 'Alias1',
                'column' => 'column1',
                'columnAlias' => 'alias1_column1'
            ],
            [
                'alias' => 'Alias2',
                'column' => 'column2',
                'columnAlias' => 'alias2_column2'
            ]
        ];
        $join = [
            [
                'model' => 'Model2',
                'alias' => 'Alias2'
            ]
        ];

        $queryParameters = $this->getMockBuilder(
                'Sevavietl\GridCompanion\QueryParameters'
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->setAttribute(
            $queryParameters,
            'definitions',
            $columnDefinitions
        );

        // Act
        $this->invokeMethod(
            $queryParameters,
            'setSelectAndJoin',
            [$columnDefinitions->getDefinitions()]
        );

        // Assert
        $this->assertAttributeEquals($select, 'select', $queryParameters);
        $this->assertAttributeEquals($join, 'join', $queryParameters);
    }

    public function testSetRowsInterval()
    {
        // Arrange
        $queryParameters = new QueryParameters($this->columnDefinitions);
        $rowsInterval = new RowsInterval(50, 100);

        // Act
        $queryParameters->setRowsInterval($rowsInterval);

        // Assert
        $this->assertAttributeEquals(50, 'startRow', $queryParameters);
        $this->assertAttributeEquals(100, 'endRow', $queryParameters);
    }

    public function testDecorateWithRowsInterval()
    {
        // Arrange
        $queryParameters = new QueryParameters($this->columnDefinitions);

        $this->setAttribute($queryParameters, 'startRow', 50);
        $this->setAttribute($queryParameters, 'endRow', 100);

        $expectedParameters = [
            'startRow' => 50,
            'endRow'   => 100
        ];

        // Act
        $this->invokeMethod($queryParameters, 'decorateWithRowsIntervals');

        // Assert
        $this->assertAttributeEquals(
            $expectedParameters,
            'parameters',
            $queryParameters
        );
    }

    public function testDecorateWithRowsIntervalWhenNoRowsIntervalSet()
    {
        // Arrange
        $queryParameters = new QueryParameters($this->columnDefinitions);

        $expectedParameters = [];

        // Act
        $this->invokeMethod($queryParameters, 'decorateWithRowsIntervals');

        // Assert
        $this->assertAttributeEquals(
            $expectedParameters,
            'parameters',
            $queryParameters
        );
    }

    public function testSetFilters()
    {
        // Arrange
        $queryParameters = new QueryParameters($this->columnDefinitions);
        $filterModel = [
            'statuses_status' => ['Cancelled', 'Confirmed', 'Lost']
        ];

        // Act
        $queryParameters->setFilters($filterModel);

        // Assert
        $this->assertAttributeEquals($filterModel, 'filters', $queryParameters);
    }

    public function testDecorateWithFilters()
    {
        // Arrange
        $queryParameters = new QueryParameters($this->columnDefinitions);
        $filterModel = [
            'statuses_status' => ['Cancelled', 'Confirmed', 'Lost']
        ];

        $this->setAttribute($queryParameters, 'filters', $filterModel);

        $expectedParameters = [
            'filters' => [
                'statuses_status' => ['Cancelled', 'Confirmed', 'Lost']
            ]
        ];

        // Act
        $this->invokeMethod($queryParameters, 'decorateWithFilters');

        // Assert
        $this->assertAttributeEquals(
            $expectedParameters,
            'parameters',
            $queryParameters
        );
    }

    public function testDecorateWithFiltersWhenFiltersAreEmpty()
    {
        // Arrange
        $queryParameters = new QueryParameters($this->columnDefinitions);

        $expectedParameters = [
            'filters' => []
        ];

        // Act
        $this->invokeMethod($queryParameters, 'decorateWithFilters');

        // Assert
        $this->assertAttributeEquals(
            $expectedParameters,
            'parameters',
            $queryParameters
        );
    }

    public function testGetQueryParams()
    {
        // Arrange
        $select = [
            [
                'alias' => 'Alias1',
                'column' => 'column1',
                'columnAlias' => 'alias1_column1'
            ],
            [
                'alias' => 'Alias2',
                'column' => 'column2',
                'columnAlias' => 'alias2_column2'
            ]
        ];
        $from = [
            'model' => 'Model1',
            'alias' => 'Alias1'
        ];
        $join = [
            [
                'model' => 'Model2',
                'alias' => 'Alias2'
            ]
        ];

        $expectedQueryParams = [
            'select'  => $select,
            'from'    => $from,
            'join'    => $join,
            'filters' => [],
            'sort'    => [],
        ];

        $queryParameters = new QueryParameters($this->columnDefinitions);

        // Act
        $actualQueryParameters = $queryParameters->getQueryParameters();

        // Assert
        $this->assertSame(
            $expectedQueryParams,
            $actualQueryParameters
        );
    }
}
