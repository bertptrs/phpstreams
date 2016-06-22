<?php

namespace phpstreams;

use IteratorAggregate;
use phpstreams\exception\InvalidStreamException;
use phpstreams\operations\FilterOperation;
use phpstreams\operations\MappingOperation;
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
}
