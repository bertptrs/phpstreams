<?php

namespace phpstreams\tests\unit;

use DirectoryIterator;
use phpstreams\Stream;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the Stream class.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class StreamTest extends TestCase
{

    /**
     * Test whether the source checker actually works.
     */
    public function testIsValidSource()
    {
        $this->assertTrue(
            Stream::isValidSource([]),
            'Arrays should be valid sources.'
        );

        $this->assertTrue(
            Stream::isValidSource(new DirectoryIterator(__DIR__)),
            'Iterators should be valid sources.'
        );

        $this->assertFalse(
            Stream::isValidSource("Not a stream"),
            "Strings should be invalid sources."
        );
    }

    public function testConstructor()
    {
        $value = new Stream([]);

        $this->assertInstanceOf("Traversable", $value);
    }

    public function testStaticCreation()
    {
        $value = Stream::of([]);

        $this->assertInstanceOf("Traversable", $value);
    }

    /**
     * @test
     * @expectedException phpstreams\exception\InvalidStreamException
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

    public function testCount()
    {
        $stream = new Stream([1, 2, 3, 4, 5, 6]);

        $this->assertEquals(6, $stream->count());
        $this->assertEquals(6, count($stream));

        $filteredStream = $stream->filter(function ($value) {
            return $value % 2 == 1;
        });

        $this->assertEquals(3, $filteredStream->count());
    }

    public function testToArray()
    {
        $stream = new Stream([1, 2, 3, 4, 5, 6]);

        $result = $stream->skip(4)->toArray();

        $this->assertEquals([
            4 => 5,
            5 => 6
        ], $result);

        $this->assertEquals([5, 6], $stream->skip(4)->toArray(false));
    }

    public function testReduce()
    {
        $instance = new Stream([1, 2, 3, 4]);

        $result = $instance->reduce(0, function ($a, $b) {
            return $a + $b;
        });

        $this->assertEquals(10, $result);
    }

    /**
     * Test the collect method.
     */
    public function testCollect()
    {
        $instance = new Stream([1, 2, 3, 4]);

        $collector = $this->getMockBuilder('phpstreams\Collector')
            ->getMock();

        $collector->expects($this->exactly(4))
            ->method('add');

        $collector->expects($this->once())
            ->method('get')
            ->willReturn(42);

        $this->assertEquals(42, $instance->collect($collector));
    }

    public function testFirst()
    {
        $stream = new Stream([4, 5, 6, 7]);
        $emptyStream = new Stream([]);

        $this->assertEquals(4, $stream->first());

        $this->assertEquals(null, $emptyStream->first());

        $this->assertEquals('empty', $emptyStream->first('empty'));

        $result = $stream->filter(function ($a) {
            return $a > 10;
        })->first();

        $this->assertEquals(null, $result);

        $result = $stream->filter(function ($a) {
            return $a > 5;
        })->first();

        $this->assertEquals(6, $result);
    }

    public function testIsSortedWithSortedSource()
    {
        $sortedSource = $this->getMockBuilder('phpstreams\Stream')
            ->disableOriginalConstructor()
            ->getMock();

        $sortedSource->expects($this->once())
            ->method('isSorted')
            ->willReturn(true);

        $instance = new Stream($sortedSource);

        $this->assertTrue($instance->isSorted());
    }

    public function testIsSortedWithUnsortedSource()
    {
        $unsortedSource = $this->getMockBuilder('phpstreams\Stream')
            ->disableOriginalConstructor()
            ->getMock();

        $unsortedSource->expects($this->once())
            ->method('isSorted')
            ->willReturn(false);

        $instance = new Stream($unsortedSource);

        $this->assertFalse($instance->isSorted());
    }

    public function testIsSortedWithArray()
    {
        $instance = new Stream([]);

        $this->assertFalse($instance->isSorted());
    }
}
