<?php

namespace phpstreams\tests\unit;

use phpstreams\Collectors;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the various collectors.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class CollectorsTest extends TestCase
{
    public function testAveraging()
    {
        $instance = Collectors::averaging();

        $instance->add("a", 6);
        $instance->add("b", 9);
        $instance->add("21", 4);
        $instance->add("foo", 21);

        $this->assertEquals(10, $instance->get());
    }

    public function testJoining()
    {
        $instance = Collectors::joining();

        $instance->add("a", "b");
        $instance->add("foo", "bar");
        $instance->add(1, 2);

        $this->assertEquals("bbar2", $instance->get());
    }

    public function testJoiningWithDelimiter()
    {
        $instance = Collectors::joining(",");

        $instance->add("a", "b");
        $instance->add("foo", "bar");
        $instance->add(1, 2);

        $this->assertEquals("b,bar,2", $instance->get());
    }

    public function testReducing()
    {
        $instance = Collectors::reducing(1, function ($a, $b) {
            return $a * $b;
        });

        $instance->add("foo", 2);
        $instance->add("bar", 3);
        $instance->add("baz", 4);

        $this->assertEquals(24, $instance->get());
    }

    public function testMapping()
    {
        $instance = Collectors::mapping(function ($k, $v) {
            return $k . "a";
        }, function($k, $v) {
            return $v + 10;
        });

        $instance->add("a", 2);
        $instance->add("b", 3);
        $instance->add("c", 4);

        $this->assertEquals([
            "aa" => 12,
            "ba" => 13,
            "ca" => 14
        ], $instance->get());


        $instance = Collectors::mapping(function ($k, $v) {
            return $k . "a";
        });

        $instance->add("a", 2);
        $instance->add("b", 3);
        $instance->add("c", 4);

        $this->assertEquals([
            "aa" => 2,
            "ba" => 3,
            "ca" => 4
        ], $instance->get());
    }
}
