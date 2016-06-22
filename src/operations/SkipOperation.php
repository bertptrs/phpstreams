<?php

namespace phpstreams\operations;

use InvalidArgumentException;
use phpstreams\Stream;
use Traversable;

/**
 * A stream operation that skips the first few items.
 *
 * @author Bert Peters <bert.ljpeters@gmail.com>
 */
class SkipOperation extends Stream
{
    /**
     * @var int
     */
    private $toSkip;

    /**
     * Construct a new skipping stream.
     *
     * @param Traversable $source
     * @param int $toSkip The number of items to skip.
     * @throws InvalidArgumentException if the number to skip is less than 0.
     */
    public function __construct($source, $toSkip)
    {
        parent::__construct($source);

        if ($toSkip < 0) {
            throw new InvalidArgumentException("To skip should be >= 0.");
        }

        $this->toSkip = $toSkip;
    }

    public function getIterator()
    {
        $toSkip = $this->toSkip;

        foreach ($this->source as $key => $value) {
            if ($toSkip > 0) {
                $toSkip--;
                continue;
            }

            yield $key => $value;
        }
    }
}
