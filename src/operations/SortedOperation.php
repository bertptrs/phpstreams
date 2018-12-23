<?php

namespace phpstreams\operations;

use ArrayIterator;
use phpstreams\exception\InvalidStreamException;
use phpstreams\Stream;
use Traversable;

/**
 * A sorting stream.
 *
 * This class yields its values sorted. Sort is done using the default asort()
 * function, or uasort if a sorting function was given.
 *
 * Note that by the nature of sorting things, the entire previous source will be
 * read into memory in order to sort it. This may be a performance issue.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class SortedOperation extends Stream
{
    /**
     * @var callable Sort comparator.
     */
    private $sort;

    public function __construct($source, callable $sort = null)
    {
        parent::__construct($source);

        $this->sort = $sort;
    }

    public function getIterator()
    {
        // Convert the source to an array, for sorting.
        if ($this->source instanceof Traversable) {
            $data = iterator_to_array($this->source);
        } elseif (is_array($this->source)) {
            $data = $this->source;
        } else {
            throw new InvalidStreamException("Cannot handle stream of type ".gettype($this->source));
        }

        if ($this->sort != null) {
            uasort($data, $this->sort);
        } else {
            asort($data);
        }

        return new ArrayIterator($data);
    }

    public function isSorted()
    {
        return true;
    }
}
