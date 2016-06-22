<?php

namespace phpstream\tests\unit\operations;

use InvalidArgumentException;
use phpstreams\operations\SkipOperation;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the skip operation.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class SkipOperationTest extends PHPUnit_Framework_TestCase
{
    public function testSkip()
    {
        $instance = new SkipOperation([1, 2, 3, 4], 2);

        $result = iterator_to_array($instance);

        $expected = [
            2 => 3,
            3 => 4,
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidSkip()
    {
        new SkipOperation([], -3);
    }
}
