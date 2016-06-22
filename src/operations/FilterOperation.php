<?php

namespace phpstreams\operations;

/**
 * Class to implement the "filter" method.
 *
 * This stream takes a callback and yields every entry for which the callback
 * returns something truthy.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class FilterOperation extends AbstractCallbackOperation
{
    public function getIterator()
    {
        $callback = $this->callback;

        foreach ($this->source as $key => $value) {
            if ($callback($value)) {
                yield $key => $value;
            }
        }
    }
}
