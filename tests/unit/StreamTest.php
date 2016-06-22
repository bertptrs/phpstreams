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
}
