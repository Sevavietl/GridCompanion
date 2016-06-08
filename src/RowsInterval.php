<?php

namespace Sevavietl\GridCompanion;

use InvalidArgumentException;
use DomainException;

class RowsInterval
{
    protected $startRow;
    protected $endRow;

    public function __construct($startRow, $endRow)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;

        $this->validate();
    }

    protected function validate()
    {
        if (
            !is_integer($this->startRow)
            || !is_integer($this->endRow)
        ) {
            throw new InvalidArgumentException(
                '$startRow and $endRow must be valid integers.'
            );
        }

        if ($this->startRow > $this->endRow) {
            throw new DomainException(
                '$startRow must be lesser than $endRow.'
            );
        }
    }

    public function getStartRow()
    {
        return $this->startRow;
    }

    public function getEndRow()
    {
        return $this->endRow;
    }
}
