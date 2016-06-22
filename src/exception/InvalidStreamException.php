<?php

namespace phpstreams\exception;

/**
 * Exception thrown when a stream is invalid.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class InvalidStreamException extends \InvalidArgumentException
{

    public function __construct($message = "Invalid stream", $code = 0,
                                \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
