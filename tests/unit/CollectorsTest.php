<?php

namespace phpstreams\tests\unit;

use phpstreams\Collectors;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the various collectors.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class CollectorsTest extends PHPUnit_Framework_TestCase
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
}
