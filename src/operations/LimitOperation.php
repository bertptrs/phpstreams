<?php

namespace phpstreams\operations;

use InvalidArgumentException;
use phpstreams\Stream;

/**
 * A stream operation limiting its output to some number of elements.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class LimitOperation extends Stream
{
    private $limit;

    /**
     * Construct a limited stream.
     *
     * @param type $source
     * @param type $limit The number of items to show, at most.
     * @throws InvalidArgumentException
     */
    public function __construct($source, $limit)
    {
        parent::__construct($source);

        if ($limit < 0) {
            throw new InvalidArgumentException("Limit should be at least 0.");
        }

        $this->limit = $limit;
    }

    public function getIterator()
    {
        $limit = $this->limit;

        foreach ($this->source as $key => $value) {
            if (--$limit < 0) {
                return;
            }

            yield $key => $value;
        }
    }
}
