<?php

namespace phpstreams;

/**
 * A collection of utilities related to functions.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
abstract class Functions
{
    /**
     * Get an identity function.
     *
     * @return callable A function that returns its first argument.
     */
    public static function identity()
    {
        return function ($value) {
            return $value;
        };
    }

    /**
     * Combine two functions into one.
     *
     * This method creates a function that effectively is b(a(value)).
     *
     * @param callable $a
     * @param callable $b
     * @return callable
     */
    public static function combine(callable $a, callable $b)
    {
        return function ($value) use ($a, $b) {
            return $b($a($value));
        };
    }
}
