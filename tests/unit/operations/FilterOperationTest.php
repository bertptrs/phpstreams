<?php

namespace phpstream\tests\unit\operations;

use phpstreams\operations\FilterOperation;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the Filter Operation.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class FilterOperationTest extends PHPUnit_Framework_TestCase
{

    public function testFiltering()
    {
        $filter = new FilterOperation([1, 2, 3, 4, 5],
            function ($value) {
            return $value % 2 == 0;
        });

        $result = iterator_to_array($filter);

        $this->assertEquals([
            1 => 2,
            3 => 4
            ], $result);
    }
}
