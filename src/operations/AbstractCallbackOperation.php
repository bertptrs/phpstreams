<?php

namespace phpstreams\operations;

use phpstreams\Stream;

/**
 * Base class to implement callback based operations on the stream.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class AbstractCallbackOperation extends Stream
{
    /**
     * @var callable
     */
    protected $callback;

    public function __construct($source, callable $callback)
    {
        parent::__construct($source);
        $this->callback = $callback;
    }
}
