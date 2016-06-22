<?php

namespace phpstream\tests\unit\operations;

use ArrayIterator;
use phpstreams\operations\SortedOperation;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the SortedOperation.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class SortedOperationTest extends PHPUnit_Framework_TestCase
{
    public function testSorted()
    {
        $instance = new SortedOperation([6, 2, 9, 4, 7, 12, 3, 1]);

        $result = iterator_to_array($instance);
        $expected = [
            7 => 1,
            1 => 2,
            6 => 3,
            3 => 4,
            0 => 6,
            4 => 7,
            2 => 9,
            5 => 12,
        ];

        $this->assertEquals($expected, $result);
    }

    public function testSortedWithIterator()
    {
        $instance = new SortedOperation(new ArrayIterator([6, 2, 9, 4, 7, 12, 3, 1]));

        $result = iterator_to_array($instance);
        $expected = [
            7 => 1,
            1 => 2,
            6 => 3,
            3 => 4,
            0 => 6,
            4 => 7,
            2 => 9,
            5 => 12,
        ];

        $this->assertEquals($expected, $result);
    }

    public function testSortedWithCallback()
    {
        // Test by sorting modulo 7.
        $instance = new SortedOperation([6, 21, 17, 44, 40], function ($a, $b) {
            if ($a % 7 < $b % 7) {
                return -1;
            }

            return $a % 7 > $b % 7;
        });

        $result = iterator_to_array($instance);

        $expected = [
            1 => 21,
            3 => 44,
            2 => 17,
            4 => 40,
            0 => 6,
        ];

        $this->assertEquals($expected, $result);
    }
}
