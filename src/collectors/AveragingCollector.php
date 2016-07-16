<?php

namespace phpstreams\collectors;

use phpstreams\Collector;
use phpstreams\Collectors;

/**
 * Collector that generates an avarage.
 *
 * This class should not be used directly. Instead, use the Collectors class.
 *
 * @see Collectors
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class AveragingCollector implements Collector
{
    private $runningSum = 0.0;

    private $count = 0;

    public function add($key, $value)
    {
        $this->runningSum += $value;
        $this->count++;
    }

    public function get()
    {
        return $this->runningSum / $this->count;
    }
}
