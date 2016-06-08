<?php

use Sevavietl\GridCompanion\Grid;
use Sevavietl\GridCompanion\RowsInterval;
use Sevavietl\GridCompanion\ColumnDefinitionsFactory;
use Sevavietl\GridCompanion\Column\Column;
use Sevavietl\GridCompanion\Column\Properties\Field;
use Sevavietl\GridCompanion\Column\Properties\HeaderName;
use Sevavietl\GridCompanion\Column\Properties\Width;

class GridTest extends TestCase
{
    protected $gridClass = Grid::class;

    protected $grid;

    protected function setUp()
    {
        $schema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field' => 'column1',
                'width' => 120,
            ],
            [
                'headerName' => 'Column 2',
                'field' => 'column2',
                'width' => 120,
            ],
        ];

        $dataProvider = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Contracts\\DataProviderInterface'
        )
        ->getMock();

        $mock = $this->getMockBuilder($this->gridClass)
                    //  ->setMethods()
                     ->disableOriginalConstructor()
                     ->getMockForAbstractClass();
        $mock->expects($this->any())
             ->method('getSchema')
             ->will($this->returnValue($schema));
        $mock->__construct($dataProvider);

        $this->grid = $mock;

        parent::setUp();
    }

    protected function tearDown()
    {
        unset($grid);

        parent::tearDown();
    }

    public function testInstantiation()
    {
        // Arrange
        $expectedSchema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field' => 'column1',
                'width' => 120,
            ],
            [
                'headerName' => 'Column 2',
                'field' => 'column2',
                'width' => 120,
            ],
        ];

        // Assert
        $this->assertAttributeEquals(
            $expectedSchema,
            'schema',
            $this->grid
        );
    }

    public function testValidateSchema()
    {
        // Arrange
        $mock = $this->getMockBuilder($this->gridClass)
            //  ->setMethods()
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $schema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field' => 'column1',
                'width' => 120,
            ],
            [
                'headerName' => 'Column 2',
                'field' => 'column2',
                'width' => 120,
            ],
        ];

        $this->setAttribute($mock, 'schema', $schema);

        // Act
        $this->invokeMethod($mock, 'validateSchema');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateSchemaThrowsExceptionWhenNoModel()
    {
        // Arrange
        $mock = $this->getMockBuilder($this->gridClass)
            //  ->setMethods()
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $schema = [
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field' => 'column1',
                'width' => 120,
            ],
            [
                'headerName' => 'Column 2',
                'field' => 'column2',
                'width' => 120,
            ],
        ];

        $this->setAttribute($mock, 'schema', $schema);

        // Act
        $this->invokeMethod($mock, 'validateSchema');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateSchemaThrowsExceptionWhenNoAlias()
    {
        // Arrange
        $mock = $this->getMockBuilder($this->gridClass)
            //  ->setMethods()
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $schema = [
            'model' => 'Model',
            [
                'headerName' => 'Column 1',
                'field' => 'column1',
                'width' => 120,
            ],
            [
                'headerName' => 'Column 2',
                'field' => 'column2',
                'width' => 120,
            ],
        ];

        $this->setAttribute($mock, 'schema', $schema);

        // Act
        $this->invokeMethod($mock, 'validateSchema');
    }

    public function testSetColumnDefinitions()
    {
        // Arrange
        $mock = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $schema = [
            'model' => 'Model',
            'alias' => 'Alias',
            [
                'headerName' => 'Column 1',
                'field' => 'column1',
                'width' => 120,
            ],
            [
                'headerName' => 'Column 2',
                'field' => 'column2',
                'width' => 120,
            ],
        ];

        $this->setAttribute($mock, 'schema', $schema);
        $this->setAttribute(
            $mock,
            'columnDefinitionsFactory',
            new ColumnDefinitionsFactory
        );

        // Act
        $this->invokeMethod($mock, 'setColumnDefinitions');

        // Assert
        $this->assertAttributeNotEmpty('columnDefinitions', $mock);
    }

    public function testSetQueryParameters()
    {
        // Arrange
        $mock = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $columnDefinitions = $this->getMockBuilder(
         'Sevavietl\GridCompanion\\ColumnDefinitions'
        )
        ->disableOriginalConstructor()
        ->setMethods(['getQueryParams', 'getDefinitions'])
        ->getMock();
        $columnDefinitions->expects($this->any())
        ->method('getQueryParams')
        ->willReturn(['params']);
        $columnDefinitions->expects($this->any())
        ->method('getDefinitions')
        ->willReturn(new SplQueue);

        $this->setAttribute($mock, 'columnDefinitions', $columnDefinitions);

        // Act
        $this->invokeMethod($mock, 'setQueryParameters');

        // Assert
        $this->assertAttributeNotEmpty('queryParameters', $mock);
    }

    public function testSetRowsIntervalFromRequest()
    {
        // Assert
        $params = [
            'startRow' => 50,
            'endRow'   => 100
        ];

        $rowsInterval = new RowsInterval(
            $params['startRow'],
            $params['endRow']
        );

        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->setMethods(['setRowsInterval'])
             ->getMockForAbstractClass();
        $grid->expects($this->once())
        ->method('setRowsInterval')
        ->with($rowsInterval);

        // Act
        $this->invokeMethod(
            $grid,
            'setRowsIntervalFromRequest',
            [$params]
        );
    }

    public function testSetFilterModelFromRequest()
    {
        // Assert
        $params = [
            'filterModel' => [
                'statuses_status' => ['Cancelled', 'Confirmed', 'Lost']
            ]
        ];

        $filterModel = $params['filterModel'];

        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->setMethods(['setFilterModel'])
             ->getMockForAbstractClass();
        $grid->expects($this->once())
        ->method('setFilterModel')
        ->with($filterModel);

        // Act
        $this->invokeMethod(
            $grid,
            'setFilterModelFromRequest',
            [$params]
        );
    }

    public function testGetData()
    {
        // Arrange
        $columnDefinitions = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\ColumnDefinitions'
        )
        ->disableOriginalConstructor()
        ->setMethods(['getQueryParams', 'getDefinitions'])
        ->getMock();
        $columnDefinitions->expects($this->any())
        ->method('getQueryParams')
        ->willReturn(['params']);
        $columnDefinitions->expects($this->any())
        ->method('getDefinitions')
        ->willReturn(new SplQueue);

        $queryParameters = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\QueryParameters'
        )
        ->setMethods(['getQueryParameters'])
        ->disableOriginalConstructor()
        ->getMock();
        $queryParameters->expects($this->once())
        ->method('getQueryParameters')
        ->willReturn(['params']);

        $dataProvider = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Contracts\\DataProviderInterface'
        )
        ->setMethods(['getData'])
        ->getMock();
        $dataProvider->expects($this->once())
        ->method('getData')
        ->with($this->equalTo(['params']))
        ->willReturn(['data']);

        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->setMethods(['setQueryParameters'])
             ->getMockForAbstractClass();
        $grid->expects($this->once())
            ->method('setQueryParameters');

        $this->setAttribute($grid, 'columnDefinitions', $columnDefinitions);
        $this->setAttribute($grid, 'dataProvider', $dataProvider);
        $this->setAttribute($grid, 'queryParameters', $queryParameters);

        // Act
        $data = $grid->getData();

        // Assert
        $this->assertEquals(['data'], $data);
    }

    public function testGetColumnDefinitions()
    {
        // Arrange
        $columnDefinitions = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\ColumnDefinitions'
        )
        ->disableOriginalConstructor()
        ->setMethods(['toArray'])
        ->getMock();
        $columnDefinitions->expects($this->once())
        ->method('toArray')
        ->willReturn(['columnDefinitions']);

        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $this->setAttribute($grid, 'columnDefinitions', $columnDefinitions);

        // Act
        $columnDefinitions = $grid->getColumnDefinitions();

        // Assert
        $this->assertEquals(['columnDefinitions'], $columnDefinitions);
    }

    public function testSetDataProvider()
    {
        // Arrange
        $dataProvider = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\Contracts\\DataProviderInterface'
        )
        ->getMock();

        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        // Act
        $grid->setDataProvider($dataProvider);

        // Assert
        $this->assertAttributeEquals(
            $dataProvider,
            'dataProvider',
            $grid
        );
    }

    public function testSetRowsInterval()
    {
        // Arrange
        $rowsInterval = new RowsInterval(50, 100);

        $queryParameters = $this->getMockBuilder(
         'Sevavietl\GridCompanion\\QueryParameters'
        )
        ->setMethods(['setRowsInterval'])
        ->disableOriginalConstructor()
        ->getMock();
        $queryParameters->expects($this->once())
        ->method('setRowsInterval')
        ->with($rowsInterval);

        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $this->setAttribute($grid, 'queryParameters', $queryParameters);

        // Act
        $grid->setRowsInterval($rowsInterval);
    }

    public function testSetFilterModel()
    {
        // Arrange
        $filterModel = [
            'statuses_status' => ['Cancelled', 'Confirmed', 'Lost']
        ];

        $queryParameters = $this->getMockBuilder(
         'Sevavietl\GridCompanion\\QueryParameters'
        )
        ->setMethods(['setFilters'])
        ->disableOriginalConstructor()
        ->getMock();
        $queryParameters->expects($this->once())
        ->method('setFilters')
        ->with($filterModel);

        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $this->setAttribute($grid, 'queryParameters', $queryParameters);

        // Act
        $grid->setFilterModel($filterModel);
    }

    public function testSetEnabledColumnsIds()
    {
        // Arrange
        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $enabledColumnIds = [1, 2, 3];

        $columnDefinitionsFactory = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\ColumnDefinitionsFactory'
        )
        ->disableOriginalConstructor()
        ->setMethods(['setEnabledColumnIds'])
        ->getMock();

        $columnDefinitionsFactory->expects($this->once())
        ->method('setEnabledColumnIds')
        ->with($enabledColumnIds);

        $this->setAttribute(
            $grid,
            'columnDefinitionsFactory',
            $columnDefinitionsFactory
        );

        $this->setAttribute($grid, 'columnDefinitions', true);

        // Act
        $grid->setEnabledColumnIds($enabledColumnIds);

        // Assert
        $this->assertAttributeEmpty('columnDefinitions', $grid);
    }

    public function testSetDisabledColumnsIds()
    {
        // Arrange
        $grid = $this->getMockBuilder($this->gridClass)
             ->disableOriginalConstructor()
             ->getMockForAbstractClass();

        $disabledColumnIds = [1, 2, 3];

        $columnDefinitionsFactory = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\ColumnDefinitionsFactory'
        )
        ->disableOriginalConstructor()
        ->setMethods(['setDisabledColumnIds'])
        ->getMock();

        $columnDefinitionsFactory->expects($this->once())
        ->method('setDisabledColumnIds')
        ->with($disabledColumnIds);

        $this->setAttribute(
            $grid,
            'columnDefinitionsFactory',
            $columnDefinitionsFactory
        );

        $this->setAttribute($grid, 'columnDefinitions', true);

        // Act
        $grid->setDisabledColumnIds($disabledColumnIds);

        // Assert
        $this->assertAttributeEmpty('columnDefinitions', $grid);
    }
}
