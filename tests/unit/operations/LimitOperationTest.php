<?php

namespace phpstream\tests\unit\operations;

use InvalidArgumentException;
use phpstreams\operations\LimitOperation;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the limit stream operation.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class LimitOperationTest extends PHPUnit_Framework_TestCase
{
    public function testLimit()
    {
        $instance = new LimitOperation([1, 2, 3, 4], 2);

        $result = iterator_to_array($instance);

        $this->assertEquals([1, 2], $result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidLimit()
    {
        new LimitOperation([], -1);
    }
}
