<?php

namespace phpstreams;

use IteratorAggregate;
use phpstreams\exception\InvalidStreamException;
use phpstreams\operations\DistinctOperation;
use phpstreams\operations\FilterOperation;
use phpstreams\operations\MappingOperation;
use phpstreams\operations\SortedOperation;
use Traversable;

/**
 * Base stream class.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class Stream implements IteratorAggregate
{
    /**
     * @var Traversable Backing source for this Stream.
     */
    protected $source;

    /**
     * Construct a new stream from a traversable source.
     * 
     * @param Traversable|array $source The source to create the stream from.
     * @throws InvalidStreamException if the given source is not usable as a stream.
     */
    public function __construct($source)
    {
        if (!static::isValidSource($source)) {
            throw new InvalidStreamException();
        }

        $this->source = $source;
    }

    /**
     * Check whether a source is valid.
     * 
     * Valid sources are either an array or a Traversable.
     * 
     * @param mixed $source
     * @return boolean True if it is.
     */
    public static function isValidSource($source)
    {
        return is_array($source) || ($source instanceof Traversable);
    }

    public function getIterator()
    {
        foreach ($this->source as $key => $value) {
            yield $key => $value;
        }
    }

    /**
     * Apply a filter to the current stream.
     *
     * Note that filter, like most stream operations, preserves the key => value relationship throughout its execution. That means that there will be missing keys, if you started with a regular array.
     *
     * @param callable $filter
     * @return Stream a new stream yielding only the elements for which the callback returns true.
     */
    public function filter(callable $filter)
    {
        return new FilterOperation($this, $filter);
    }

    /**
     * Map the values in the stream to another stream.
     *
     * @param callable $mapping
     * @return Stream a new stream with the variables mapped.
     */
    public function map(callable $mapping)
    {
        return new MappingOperation($this, $mapping);
    }

    /**
     * Enforce distinct values.
     *
     * This stream will yield every distinct value only once. Note that
     * internally, it uses in_array for lookups. This is problematic for large
     * numbers of distinct elements, as the complexity of this stream filter
     * becomes O(n * m) with n the number of elements and m the number of distinct
     * values.
     *
     * This mapping preserves key => value relationships, and will yield the
     * values with the first keys encountered.
     *
     * @param type $strict whether to use strict comparisons for uniqueness.
     * @return Stream
     */
    public function distinct($strict = false)
    {
        return new DistinctOperation($this, $strict);
    }

    /**
     * Get a sorted view of the stream.
     *
     * This method yields its values sorted. Sort is done using the default asort()
     * function, or uasort if a sorting function was given.
     *
     * Note that by the nature of sorting things, the entire previous source will be
     * read into memory in order to sort it. This may be a performance issue.
     *
     * @param callable $sort [optional] a callback to use for sorting.
     * @return Stream
     */
    public function sorted(callable $sort = null)
    {
        return new SortedOperation($this, $sort);
    }
}
