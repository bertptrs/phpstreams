<?php

namespace phpstreams;

use Countable;
use IteratorAggregate;
use phpstreams\exception\InvalidStreamException;
use phpstreams\operations\DistinctOperation;
use phpstreams\operations\FilterOperation;
use phpstreams\operations\FlatMapOperation;
use phpstreams\operations\LimitOperation;
use phpstreams\operations\MappingOperation;
use phpstreams\operations\SkipOperation;
use phpstreams\operations\SortedOperation;
use Traversable;

/**
 * Base stream class.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class Stream implements IteratorAggregate, Countable
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
     * Create a new stream from a traversable source.
     *
     * @param Traversable|array $source The source to create the stream from.
     * @throws InvalidStreamException if the given source is not usable as a stream.
     */
    public static function of($source)
    {
        return new static($source);
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

    /**
     * Skip the first few elements.
     *
     * This method discards the first few elements from the stream, maintaining
     * key => value relations.
     *
     * @param int $toSkip
     * @return Stream
     */
    public function skip($toSkip)
    {
        return new SkipOperation($this, $toSkip);
    }

    /**
     * Limit the stream to some number of elements.
     *
     * All elements after the limit are not considered or generated, so this can
     * be used with infinite streams.
     *
     * @param int $limit The maximum number of elements to return.
     * @return Stream
     */
    public function limit($limit)
    {
        return new LimitOperation($this, $limit);
    }

    /**
     * Return true if any element matches the predicate.
     *
     * This method short-circuits, so if any item matches the predicate, no
     * further items are evaluated.
     *
     * @param callable $predicate
     * @return boolean
     */
    public function any(callable $predicate = null)
    {
        if ($predicate === null) {
            $predicate = Functions::identity();
        }

        foreach ($this as $value) {
            if ($predicate($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return true if all element matches the predicate.
     *
     * This method short-circuits, so if any item does not match the predicate,
     * no further elements are considered.
     *
     * @param callable $predicate
     * @return boolean
     */
    public function all(callable $predicate = null)
    {
        if ($predicate === null) {
            $predicate = Functions::identity();
        }

        foreach ($this as $value) {
            if (!$predicate($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert this stream to an array.
     *
     * @param boolean $withKeys [optional] if false, return a simple array containing all values in order. If true (the default) preserve keys.
     * @return array An (associative) array of the contents of the stream.
     */
    public function toArray($withKeys = true)
    {
        return iterator_to_array($this, $withKeys);
    }

    /**
     * Count the number of elements in this stream.
     *
     * This method may consume the stream, if it is not repeatable. Use it only
     * when appropriate.
     *
     * As this is simply an implementation of the Countable interface, the count
     * function may be used instead. However, this still may consume the stream.
     *
     * @return int The number of elements in this Stream.
     */
    public function count()
    {
        return iterator_count($this);
    }

    /**
     * Reduce the stream using the given binary operation.
     *
     * The given is applied to every item in the stream in no particular order.
     * The result is then returned.
     *
     * In order for the callable to be a proper reductor, it should be:
     * <ul>
     * <li>Commutative, so op($a, $b) is equal to op($b, $a), and
     * <li>Should preserve respect the given identity, i.e. op($a, $identity) =
     * $identity.
     * </ul>
     * If any of these properties do not hold, the output of this function is
     * not defined.
     *
     * @param mixed $identity The identity element.
     * @param callable $binaryOp A reduction function, respecting the properties
     * above.
     * @return mixed
     */
    public function reduce($identity, callable $binaryOp)
    {
        $cur = $identity;

        foreach ($this as $value) {
            $cur = $binaryOp($cur, $value);
        }

        return $cur;
    }

    public function collect(Collector $collector)
    {
        foreach ($this as $key => $value) {
            $collector->add($key, $value);
        }

        return $collector->get();
    }

    /**
     * Get first element from stream
     *
     * @param $defaultValue [optional] Default value to return if stream is empty
     * @return first element if available, default value otherwise
     */
    public function first($defaultValue = null)
    {
        foreach ($this as $value) {
            return $value;
        }

        return $defaultValue;
    }

    /**
     * Flatten the underlying stream.
     *
     * This method takes each element and unpacks it into a sequence of elements.
     * All individual sequences are concatenated in the resulting stream.
     *
     * @param callable $unpacker [optional] An unpacker function that can unpack
     * elements into something iterable. Default is to use the identity function.
     * @return Stream
     */
    public function flatMap(callable $unpacker = null)
    {
        if ($unpacker == null) {
            $unpacker = Functions::identity();
        }

        return new FlatMapOperation($this, $unpacker);
    }

    /**
     * Check whether this stream is definitely sorted.
     *
     * This is used to optimize some stream operations, such as distinct(),
     * which only needs constant memory when operating on a sorted list.
     *
     * Any stream operations that potentially change the sorting order should
     * override this method to properly reflect the actual sorting order.
     *
     * @return boolean
     */
    public function isSorted()
    {
        if ($this->source instanceof Stream) {
            return $this->source->isSorted();
        } else {
            return false;
        }
    }
}
