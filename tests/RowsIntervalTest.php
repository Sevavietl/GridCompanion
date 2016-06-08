<?php

use Sevavietl\GridCompanion\RowsInterval;

class RowsIntervalTest extends TestCase
{
    protected $rowsInterval;

    protected function setUp()
    {
        $this->rowsInterval = $this->getMockBuilder(
            'Sevavietl\GridCompanion\\RowsInterval'
        )
        ->disableOriginalConstructor()
        ->getMock();
    }

    protected function tearDown()
    {

    }

    public function testValidate()
    {
        $startRow = 50;
        $endRow = 100;

        $this->setAttribute($this->rowsInterval, 'startRow', $startRow);
        $this->setAttribute($this->rowsInterval, 'endRow', $endRow);

        $this->invokeMethod($this->rowsInterval, 'validate');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidateThrowsExceptionWhenNotIntegers()
    {
        $startRow = '50';
        $endRow = 100;

        $this->setAttribute($this->rowsInterval, 'startRow', $startRow);
        $this->setAttribute($this->rowsInterval, 'endRow', $endRow);

        $this->invokeMethod($this->rowsInterval, 'validate');
    }

    /**
     * @expectedException \DomainException
     */
    public function testValidateThrowsExceptionWhenWrongBoundaries()
    {
        $startRow = 100;
        $endRow = 50;

        $this->setAttribute($this->rowsInterval, 'startRow', $startRow);
        $this->setAttribute($this->rowsInterval, 'endRow', $endRow);

        $this->invokeMethod($this->rowsInterval, 'validate');
    }

    public function testInstantiation()
    {
        $startRow = 50;
        $endRow = 100;

        $rowsInterval = new RowsInterval($startRow, $endRow);

        $this->assertAttributeEquals($startRow, 'startRow', $rowsInterval);
        $this->assertAttributeEquals($endRow, 'endRow', $rowsInterval);
    }

    public function testGetStartRow()
    {
        $startRow = 50;
        $endRow = 100;

        $rowsInterval = new RowsInterval($startRow, $endRow);

        $this->assertEquals($startRow, $rowsInterval->getStartRow());
    }

    public function testGetEndRow()
    {
        $startRow = 50;
        $endRow = 100;

        $rowsInterval = new RowsInterval($startRow, $endRow);

        $this->assertEquals($endRow, $rowsInterval->getEndRow());
    }
}
