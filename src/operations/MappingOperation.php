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
}
