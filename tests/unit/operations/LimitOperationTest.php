<?php

namespace phpstream\tests\unit\operations;

use InvalidArgumentException;
use phpstreams\operations\LimitOperation;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the limit stream operation.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class LimitOperationTest extends TestCase
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
