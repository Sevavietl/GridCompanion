<?php

namespace Sevavietl\GridCompanion;

use SplQueue;
use Traversable;

use Sevavietl\GridCompanion\Contracts\ColumnDefinition;

use Sevavietl\GridCompanion\QueryParameters;

class ColumnDefinitions
{
    protected $model;
    protected $alias;

    protected $definitions;

    protected $hash = [];

    public function __construct($model, $alias)
    {
        $this->model = $model;
        $this->alias = $alias;

        $this->definitions = new SplQueue;
    }

    public function add(ColumnDefinition $definition, array $hash = null)
    {
        if (!empty($hash)) {
            $this->addToHash($hash);
        }

        $this->definitions->enqueue($definition);

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getDefinitions()
    {
        return $this->definitions;
    }

    public function toArray()
    {
        return reduce(
            function ($definition, $carry) {
                array_push($carry, $definition->toArray());
                return $carry;
            },
            [],
            $this->definitions
        );
    }

    public function addToHash(array $hash)
    {
        $this->hash = array_merge(
            $this->hash,
            $hash
        );

        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }
}
