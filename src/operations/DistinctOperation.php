<?php

namespace phpstreams\operations;

use phpstreams\Stream;

/**
 * Unique stream.
 *
 * A stream that will only yield each distinct value once.
 *
 * @author Bert Peters <bert@bertptrs.nl>
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
        return $this->isSorted()
            ? $this->sortedIterator()
            : $this->unsortedIterator();
    }

    /**
     * Iterator for unsorted streams.
     *
     * This iterator compares the value to all previously found values to
     * decide on uniqueness.
     */
    private function unsortedIterator()
    {
        $data = [];

        foreach ($this->source as $key => $value) {
            if (!in_array($value, $data, $this->strict)) {
                $data[] = $value;
                yield $key => $value;
            }
        }
    }

    /**
     * Iterator for sorted streams.
     *
     * This method compares the current value to the previous value to decide on
     * uniqueness.
     */
    private function sortedIterator()
    {
        $prev = null;
        $first = true;

        foreach ($this->source as $key => $value) {
            if ($first || ($this->strict && $value !== $prev) || (!$this->strict && $value != $prev)) {
                yield $key => $value;
                $first = false;
                $prev = $value;
            }
        }
    }
}
