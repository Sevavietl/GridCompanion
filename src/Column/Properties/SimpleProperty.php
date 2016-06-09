<?php

namespace Sevavietl\GridCompanion\Column\Properties;

class SimpleProperty extends Property
{
    protected $name;
    protected $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function toArray()
    {
        return [
            $this->name => $this->value
        ];
    }
}
