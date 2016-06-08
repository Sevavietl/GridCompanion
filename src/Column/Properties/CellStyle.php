<?php

namespace Sevavietl\GridCompanion\Column\Properties;

use InvalidArgumentException;

/**
 * The name to render in the column header
 */
class CellStyle extends Property
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'cellStyle' => $this->getValue()
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
