<?php

namespace Sevavietl\GridCompanion\Column\Properties;

use InvalidArgumentException;
use DomainException;

/**
 * The name to render in the column header
 */
class Field extends Property
{
    protected $value;

    /**
     * Table alias for join.
     * @var string
     */
    protected $alias;

    /**
     * Table column name.
     * @var string
     */
    protected $column;

    /**
     * String is used to glue alias and column together.
     * @var string
     */
    protected $glue = '_';

    public function __construct($alias, $column)
    {
        $this->alias = $alias;
        $this->column = $column;

        $this->validate();

        $this->setValue([$alias, $column]);
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * [getAlias description]
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * [getColumn description]
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'field' => $this->getValue()
        ];
    }

    /**
     * Set value by glueing alias and column together.
     * @param array $pieces [description]
     * @return void
     */
    protected function setValue(array $pieces)
    {
        $this->value = uncamelize(implode($this->glue, $pieces));
    }

    /**
     * @inheritdoc
     */
    protected function validate()
    {
        $this->validateAlias();
        $this->validateColumn();
    }

    /**
     * Alias must be string and must not contain spaces or glue.
     * @throws \InvalidArgumentException | \DomainException
     *
     * @return void
     */
    protected function validateAlias()
    {
        if (!is_string($this->alias)) {
            throw new InvalidArgumentException("Alias must be a string.");
        }

        if (
            strpos($this->alias, ' ') !== false
        ) {
            throw new DomainException(
                "Alias must not contain spaces."
            );
        }
    }

    /**
     * Column must be string and must not contain spaces or glue.
     * @throws \InvalidArgumentException | \DomainException
     *
     * @return void
     */
    protected function validateColumn()
    {
        if (!is_string($this->column)) {
            throw new InvalidArgumentException("Column must be a string.");
        }


        if (
            strpos($this->column, ' ') !== false
        ) {
            throw new DomainException(
                "Column must not contain spaces."
            );
        }
    }
}
