<?php

namespace phpstreams;

/**
 * Collector for the streams interface.
 *
 * A collector can be thought of as a stateful reductor. It is initialized (in
 * some way) and then elements are insterted one by one.
 *
 * Finally, the result is queried in the get method.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
interface Collector
{
    /**
     * Add a new value to the collector.
     *
     * @param mixed $key The current key.
     * @param mixed $value The current value.
     */
    public function add($key, $value);

    /**
     * Get the final result from the collector.
     *
     * @return mixed Whatever the result of this collector is.
     */
    public function get();
}
