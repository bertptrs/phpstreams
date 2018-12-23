<?php

namespace phpstream\tests\unit\operations;

use phpstreams\operations\MappingOperation;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for the mapping operation.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class MappingOperationTest extends TestCase
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
