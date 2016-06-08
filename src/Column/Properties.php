<?php

namespace Sevavietl\GridCompanion\Column;

use Sevavietl\GridCompanion\Column\Properties\Property;

trait Properties
{
    /**
     * Column properties.
     * @var \Sevavietl\GridCompanion\Column\PropertiesStorage
     */
    protected $properties;

    /**
     * Add property for the column.
     * @param \Sevavietl\GridCompanion\Column\Properties\PropertyProperty $property [description]
     */
    public function addProperty(Property $property)
    {
        $this->properties->attach($property);

        return $this;
    }

    /**
     * [getProperties description]
     * @return \Sevavietl\GridCompanion\Column\PropertiesStorage [description]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Array representation of the property.
     * @return array [description]
     */
    public function toArray()
    {
        return reduce(
            function ($property, $carry) {
                return array_merge(
                    $carry,
                    $property->toArray()
                );
            },
            [],
            $this->properties
        );
    }
}
