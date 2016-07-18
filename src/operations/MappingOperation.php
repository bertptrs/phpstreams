<?php

namespace phpstreams\operations;

/**
 * Class implementing a mapping operation.
 *
 * This stream uses a callback to map values from a stream to a function.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class MappingOperation extends AbstractCallbackOperation
{
    public function getIterator()
    {
        $callback = $this->callback;

        foreach ($this->source as $key => $value) {
            yield $key => $callback($value);
        }
    }

    /**
     * Override the parent sorting method.
     *
     * Mapping does not neccesarily preserve sorting order, so this method
     * always returns false.
     *
     * @return boolean
     */
    public function isSorted()
    {
        return false;
    }
}
