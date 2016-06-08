<?php

namespace Sevavietl\GridCompanion\Column\Properties;

use InvalidArgumentException;

/**
 * The name to render in the column header
 */
class ColId extends Property
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'colId' => $this->getValue()
        ];
    }

    /**
     * @inheritdoc
     */
    protected function validate()
    {
        if (!is_string($this->value)) {
            throw new InvalidArgumentException("Header name must be a string");
        }
    }
}
