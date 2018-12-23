<?php

namespace phpstream\tests\unit\operations;

use phpstreams\operations\DistinctOperation;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the DistinctOperation class.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class DistinctOperationTest extends TestCase
{
    public function testDistinct()
    {
        $data = [1, 4, 2, 3, '1', 3];

        $instance = new DistinctOperation($data, false);

        $result = iterator_to_array($instance);

        $this->assertEquals([
            0 => 1,
            1 => 4,
            2 => 2,
            3 => 3,
        ], $result);
    }

    public function testStrictDistinct()
    {
        $data = [1, 4, 2, 3, '1', 3];

        $instance = new DistinctOperation($data, true);

        $result = iterator_to_array($instance);

        $this->assertEquals([
            0 => 1,
            1 => 4,
            2 => 2,
            3 => 3,
            4 => '1',
        ], $result);
    }
}
