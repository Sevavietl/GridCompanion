<?php

namespace Sevavietl\GridCompanion\Column;

trait DefinitionTypeChecker
{
    public function isColumnGroup()
    {
        return property_exists($this, 'columns');
    }
}
