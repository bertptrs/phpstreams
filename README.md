# PHPStreams

[![Latest Stable Version](https://poser.pugx.org/bertptrs/phpstreams/v/stable)](https://packagist.org/packages/bertptrs/phpstreams) [![Total Downloads](https://poser.pugx.org/bertptrs/phpstreams/downloads)](https://packagist.org/packages/bertptrs/phpstreams) [![License](https://poser.pugx.org/bertptrs/phpstreams/license)](https://packagist.org/packages/bertptrs/phpstreams) [![Build Status](https://travis-ci.org/bertptrs/phpstreams.svg?branch=master)](https://travis-ci.org/bertptrs/phpstreams)

A partial implementation of the Java 8 Streams API in PHP. PHPStreams
can use your generators, your arrays and really anything that is
[Iterable](https://wiki.php.net/rfc/iterable) and convert modify it like
you're used to using Java Streams!

Using streams and generators, you can easily sort through large amounts
of data without having to have it all in memory or in scope. Streams
also make it easier to structure your code, by (more or less) enforcing
single resposibility.

The library is compatible with PHP 5.5.9 and up.

## Installation

PHPStreams can be installed using Composer. Just run `composer require
bertptrs/phpstreams` in your project root!

## Usage

Using streams is easy. Say, we want the first 7 odd numbers in the
Fibonacci sequence. To do this using Streams, we do the following:

```php
// Define a generator for Fibonacci numbers
function fibonacci()
{
    yield 0;
    yield 1;

    $prev = 0;
    $cur = 1;

    while (true) {
        yield ($new = $cur + $prev);
        $prev = $cur;
        $cur = $new;
    }
};

// Define a predicate that checks for odd numbers
$isOdd = function($num) {
    return $num % 2 == 1;
};

// Create our stream.
$stream = new phpstreams\Stream(fibonacci());

// Finally, use these to create our result.
$oddFibo = $stream->filter($isOdd)  // Keep only the odd numbers
    ->limit(8)                      // Limit our results
    ->toArray(false);               // Convert to array, discarding keys
```

## Documentation

Documentation is mostly done using PHPDoc. I do intend to write actual
documtation if there is any interest.

## Contributing

I welcome contributions and pull requests. Please note that I do follow
PSR-2 (and PSR-4 for autoloading). Also, please submit unit tests with
your work.

GrumPHP enforces at least part of the coding standard, but do make an
effort to structure your contributions nicely.
