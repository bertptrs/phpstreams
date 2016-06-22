<?php

namespace phpstream\tests\unit\operations;

use phpstreams\operations\MappingOperation;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the mapping operation.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class MappingOperationTest extends PHPUnit_Framework_TestCase
{

    public function testMapping()
    {
        $mapping = new MappingOperation([1, 2, 3, 4, 5], function ($value) {
            return $value * 2;
        });

        $result = iterator_to_array($mapping);

        $this->assertEquals([2, 4, 6, 8, 10], $result);
    }
}
