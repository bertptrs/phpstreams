<?php

namespace phpstreams\operations;

use phpstreams\Stream;

/**
 * Unique stream.
 *
 * A stream that will only yield each distinct value once.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class DistinctOperation extends Stream
{
    private $strict;

    /**
     * Construct a unique stream.
     *
     * This stream will yield each distinct value only once.
     *
     * @param \Traversable $source
     * @param boolean $strict Whether to use strict comparisons when determining uniqueness.
     */
    public function __construct($source, $strict)
    {
        parent::__construct($source);

        $this->strict = $strict;
    }

    public function getIterator()
    {
        $data = [];

        foreach ($this->source as $key => $value) {
            if (!in_array($value, $data, $this->strict)) {
                $data[] = $value;
                yield $key => $value;
            }
        }
    }
}
