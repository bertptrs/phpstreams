<?php

namespace phpstreams;

use phpstreams\collectors\AveragingCollector;

/**
 * Utility class containing various collectors.
 *
 * For compatibility reasons, the returned collectors are actuall classes,
 * rather than anonymous classes. This is an implementation detail, and should
 * not be relied upon. The implementing classes may be removed at any point.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
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
}
