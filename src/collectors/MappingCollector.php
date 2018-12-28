<?php

namespace phpstreams\collectors;

use phpstreams\Collector;

/**
 * Collector that maps array keys and values.
 *
 * @author Bartosz Schiller <bartosz.schiller@gmail.com>
 */
class MappingCollector implements Collector
{
    /**
     * Mapped array
     *
     * @var array
     */
    private $map = [];

    /**
     * Callable for mapping key
     *
     * @var callable
     */
    private $keyMapper;

    /**
     * Callable for mapping value
     *
     * @var callable
     */
    private $valueMapper;

    public function __construct(callable $keyMapper, callable $valueMapper = null)
    {
        $this->keyMapper = $keyMapper;

        if (!is_callable($valueMapper))
        {
            $valueMapper = function ($k, $v) {
                return $v;
            };
        }

        $this->valueMapper = $valueMapper;
    }

    public function add($key, $value)
    {
        $keyMapper = $this->keyMapper;
        $valueMapper = $this->valueMapper;

        $this->map[$keyMapper($key, $value)] = $valueMapper($key, $value);
    }

    public function get()
    {
        return $this->map;
    }
}
