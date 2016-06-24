<?php

namespace Sevavietl\GridCompanion\Column\Properties;

use DomainException;

/**
 * The name to render in the column header
 */
class Filter extends Property
{
    /**
     * Filter type.
     * @var string
     */
    protected $type;

    /**
     * Filter params.
     * @var array
     */
    protected $params;

    public function __construct($type, array $params = [])
    {
        $this->type   = $type;
        $this->params = $params;
    }

    /**
     * [getType description]
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * [getParams description]
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return array_merge(
            $this->typeToArray(),
            $this->paramsToArray()
        );
    }


    /**
     * [typeToArray description]
     * @return array
     */
    protected function typeToArray()
    {
        return ['filter' => $this->getType()];
    }

    /**
     * [paramsToArray description]
     * @return array If parameters are empty then empty list will be returned.
     */
    protected function paramsToArray()
    {
        if (empty($this->params)) {
            return [];
        }

        $this->tryToResolveCallableValuesFromParams();

        return ['filterParams' => $this->getParams()];
    }

    protected function tryToResolveCallableValuesFromParams()
    {
        if (isset($this->params['values']) && is_callable($this->params['values'])) {
            $this->params['values'] = $this->params['values']();
        }
    }
}
