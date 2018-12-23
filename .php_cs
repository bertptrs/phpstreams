<?php

$finder = \PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

return \PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setRules([
        '@PSR2' => true,
    ]);