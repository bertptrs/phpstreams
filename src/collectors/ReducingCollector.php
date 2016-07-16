<?php

namespace phpstreams\collectors;

use phpstreams\Collector;

/**
 * Collector that applies a reductor, as if by the reduce method.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class ReducingCollector implements Collector
{
    /**
     * @var mixed The current value in the reductor.
     */
    private $current;

    /**
     * The reduction being applied.
     *
     * @var callable
     */
    private $reductor;

    public function __construct($identity, callable $reductor)
    {
        $this->current = $identity;

        $this->reductor = $reductor;
    }

    public function add($key, $value)
    {
        $reductor = $this->reductor;

        $this->current = $reductor($this->current, $value);
    }

    public function get()
    {
        return $this->current;
    }
}
