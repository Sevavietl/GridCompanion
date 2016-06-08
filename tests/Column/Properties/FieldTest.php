<?php

use Sevavietl\GridCompanion\Column\Properties\Field;

class FieldTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        // Arrange
        $alias = 'Alias';
        $column = 'column';
        $expectedAlias = 'Alias';
        $expectedColumn = 'column';

        // Act
        $field = new Field($alias, $column);

        // Assert
        $this->assertEquals(
            [$expectedAlias, $expectedColumn],
            [$field->getAlias(), $field->getColumn()]
        );
    }

    public function testSetValue()
    {
        // Arrange
        $alias = 'Alias';
        $column = 'column';
        $expectedValue = 'alias_column';

        // Act
        $field = new Field($alias, $column);

        // Assert
        $this->assertEquals($expectedValue, $field->getValue());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBooleanAliasValidationException()
    {
        // Arrange
        $alias = true;
        $column = 'column';

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBooleanColumnValidationException()
    {
        // Arrange
        $alias = 'Alias';
        $column = true;

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerAliasValidationException()
    {
        // Arrange
        $alias = 18;
        $column = 'column';

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testIntegerColumnValidationException()
    {
        // Arrange
        $alias = 'Alias';
        $column = 18;

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFloatAliasValidationException()
    {
        // Arrange
        $alias = 1.8;
        $column = 'column';

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFloatColumnValidationException()
    {
        // Arrange
        $alias = 'Alias';
        $column = 1.8;

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayAliasValidationException()
    {
        // Arrange
        $alias = [1, 8];
        $column = 'column';

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testArrayColumnValidationException()
    {
        // Arrange
        $alias = 'Alias';
        $column = [1, 8];

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \DomainException
     */
    public function testNotAllowedSpaceAliasValidationException()
    {
        // Arrange
        $alias = 'Alias with spaces';
        $column = 'column';

        // Act
        $field = new Field($alias, $column);
    }

    /**
     * @expectedException \DomainException
     */
    public function testNotAllowedSpaceColumnValidationException()
    {
        // Arrange
        $alias = 'Alias';
        $column = 'column with spaces';

        // Act
        $field = new Field($alias, $column);
    }

    public function testToArray()
    {
        // Arrange
        $alias = 'Alias';
        $column = 'column';
        $field = new Field($alias, $column);
        $expectedArrayProperty = [
            'field' => 'alias_column'
        ];

        // Act
        $arrayProperty = $field->toArray();

        // Assert
        $this->assertEquals($expectedArrayProperty, $arrayProperty);
    }
}
