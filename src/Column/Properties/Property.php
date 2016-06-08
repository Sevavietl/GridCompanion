<?php

namespace Sevavietl\GridCompanion\Column\Properties;

abstract class Property
{
    /**
     * [$value description]
     * @var mixed
     */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;

        $this->validate();
    }

    /**
     * [getValue description]
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Array representation of the propery.
     * @return array
     */
    abstract public function toArray();

    /**
     * Validate value of the propery.
     * @throws \InvalidArgumentException
     * @return void
     */
    abstract protected function validate();


}
