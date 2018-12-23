<?php

namespace phpstreams;

use phpstreams\collectors\AveragingCollector;
use phpstreams\collectors\ReducingCollector;

/**
 * Utility class containing various collectors.
 *
 * For compatibility reasons, the returned collectors are actuall classes,
 * rather than anonymous classes. This is an implementation detail, and should
 * not be relied upon. The implementing classes may be removed at any point.
 *
 * @author Bert Peters <bert@bertptrs.nl>
 */
class Collectors
{
    /**
     * Hidden constructor, static class.
     */
    private function __construct()
    {
    }

    /**
     * Collector that returns the average of the given elements.
     *
     * The return type of this collector is float, regardless of the type of
     * the stream elements.
     *
     * @return Collector
     */
    public static function averaging()
    {
        return new AveragingCollector();
    }

    /**
     * Get a collector that applies the given reduction to the stream.
     *
     * @param mixed $identity
     * @param callable $reduction
     * @return Collector
     */
    public static function reducing($identity, callable $reduction)
    {
        return new ReducingCollector($identity, $reduction);
    }

    /**
     * Get a collector that concatenates all the elements in the stream.
     *
     * @param string $delimiter [optional] A delimiter to insert between
     * elements. Defaults to the empty string.
     * @return Collector
     */
    public static function joining($delimiter = "")
    {
        $first = true;

        return Collectors::reducing(
            "",
            function ($current, $element) use (&$first, $delimiter) {
                if (!$first) {
                    $current .= $delimiter;
                } else {
                    $first = false;
                }

                return $current . $element;
            }
        );
    }
}
