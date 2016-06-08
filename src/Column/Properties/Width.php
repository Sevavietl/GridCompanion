<?php

namespace Sevavietl\GridCompanion\Column\Properties;

use InvalidArgumentException;

/**
 * The name to render in the column header
 */
class Width extends Property
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'width' => $this->getValue()
        ];
    }

    /**
     * @inheritdoc
     */
    protected function validate()
    {
        if (!is_int($this->value)) {
            throw new InvalidArgumentException("Header name must be string");
        }
    }
}
