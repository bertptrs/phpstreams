<?php

namespace phpstreams\tests\unit;

use DirectoryIterator;
use phpstreams\Stream;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the Stream class.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class StreamTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test whether the source checker actually works.
     */
    public function testIsValidSource()
    {
        $this->assertTrue(Stream::isValidSource([]),
            'Arrays should be valid sources.');

        $this->assertTrue(Stream::isValidSource(new DirectoryIterator(__DIR__)),
            'Iterators should be valid sources.');

        $this->assertFalse(Stream::isValidSource("Not a stream"),
            "Strings should be invalid sources.");
    }

    public function testConstructor()
    {
        $value = new Stream([]);

        $this->assertInstanceOf("Traversable", $value);
    }

    /**
     * @test
     * @expectedException \phpstreams\exception\InvalidStreamException
     */
    public function testConstructorException()
    {
        $value = new Stream("Geen source.");
    }

    public function testAll()
    {
        $stream = new Stream([1, 4, 9, 16, 25]);

        // All these integers are truthy.
        $this->assertTrue($stream->all());

        // Not all of them are even.
        $this->assertFalse($stream->all(function ($value) {
            return $value % 2 == 0;
        }));

        // All of these are squares.
        $this->assertTrue($stream->all(function ($value) {
            $root = round(sqrt($value));

            return $root * $root == $value;
        }));
    }

    public function testAny()
    {
        $this->assertTrue((new Stream([false, false, true, false]))->any());

        $this->assertFalse((new Stream([false, false, false, false]))->any());

        $stream = new Stream([1, 2, 3, 4, 42]);

        $this->assertTrue($stream->any(function ($value) {
            return $value == 42;
        }));
    }
}
