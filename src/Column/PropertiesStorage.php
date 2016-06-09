<?php

namespace Sevavietl\GridCompanion\Column;

use SplObjectStorage;
use InvalidArgumentException;
use DomainException;

use Sevavietl\GridCompanion\Column\Properties\Property;
use Sevavietl\GridCompanion\Column\Properties\SimpleProperty;

class PropertiesStorage extends SplObjectStorage
{
    public function attach($property, $data = null)
    {
        $this->validateArguments($property, $data);

        if ($this->contains($property)) {
            throw new DomainException('The identical property already in the storage');
        }

        foreach ($this as $addedProperty) {
            if (get_class($property) === get_class($addedProperty)) {
                if ($property instanceof SimpleProperty) {
                    if (key($property->toArray()) !== key($addedProperty->toArray())) {
                        continue;
                    }

                    throw new DomainException('The property ' . key($property->toArray()) . ' already in the storage');
                }

                throw new DomainException('The property of the same class ' . get_class($property) . ' already in the storage');
            }
        }

        parent::attach($property, $data);
    }

    public function contains($property)
    {
        $this->validateArguments($property, null);

        if (parent::contains($property)) {
            return true;
        }

        foreach ($this as $addedProperty) {
            if (get_class($property) === get_class($addedProperty)) {
                if ($property->toArray() === $addedProperty->toArray()) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function validateArguments($property, $data)
    {
        if (!($property instanceof Property)) {
            throw new InvalidArgumentException(
                '$property must be an instance of Sevavietl\GridCompanion\Column\Properties\Property.'
            );
        }

        if (!is_null($data)) {
            throw new InvalidArgumentException(
                'You cannot pass any data along with the property.'
            );
        }
    }
}
