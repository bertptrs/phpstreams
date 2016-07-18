<?php

namespace phpstreams\operations;

use phpstreams\Stream;

/**
 * Operation that flattens a nested source.
 *
 * This method does not preserve key => value relationships, as this would not
 * make sense with nested arrays. Instead, it produces sequential keys.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class FlatMapOperation extends Stream
{
    /**
     * @var callable Callback used to unpack items in the stream.
     */
    private $unpacker;

    /**
     * Construct a new flatmap stream.
     *
     * @param iterable $source The source to read from.
     * @param callable $unpacker Some callback that convert source stream items
     * to something iterable.
     */
    public function __construct($source, callable $unpacker)
    {
        parent::__construct($source);
        
        $this->unpacker = $unpacker;
    }

    public function getIterator()
    {
        $unpacker = $this->unpacker;

        foreach ($this->source as $value) {
            foreach ($unpacker($value) as $unpackedValue) {
                yield $unpackedValue;
            }
        }
    }

    /**
     * Override the parent sorting method.
     *
     * Flatmapping does not neccesarily preserve sorting order, so this method
     * always returns false.
     *
     * @return boolean
     */
    public function isSorted()
    {
        return false;
    }
}
