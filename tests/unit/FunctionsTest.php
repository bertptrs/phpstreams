<?php

namespace phpstreams\tests\unit;

use phpstreams\Functions;
use PHPUnit_Framework_TestCase;

/**
 * Test cases for the Functions class utility.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class FunctionsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test the Functions::identity method.
     */
    public function testIdentity()
    {
        $data = [
            'foo',
            'bar',
            42,
        ];

        $identityFunc = Functions::identity();

        $this->assertTrue(is_callable($identityFunc), "Identity function should be callable.");

        foreach ($data as $item) {
            $this->assertEquals($item, $identityFunc($item));
        }
    }

    /**
     * Test the Functions::combine method.
     */
    public function testCombine()
    {
        $a = function ($value) {
            return $value + 2;
        };

        $b = function ($value) {
            return $value * 3;
        };

        $result = Functions::combine($a, $b);

        $this->assertTrue(is_callable($result), 'Combined function should be callable.');

        $this->assertEquals($result(3), 15);
    }
}
