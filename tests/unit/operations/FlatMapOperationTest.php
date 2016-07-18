<?php

namespace phpstream\tests\unit\operations;

use phpstreams\operations\FlatMapOperation;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the FlatMapOperation.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class FlatMapOperationTest extends PHPUnit_Framework_TestCase
{
    public function testFlatMap()
    {
        $instance = new FlatMapOperation([1, 2, 3], function () {
            return [4, 5, 6];
        });

        $result = $instance->toArray();

        $this->assertEquals([4, 5, 6, 4, 5, 6, 4, 5, 6], $result);
    }
}
