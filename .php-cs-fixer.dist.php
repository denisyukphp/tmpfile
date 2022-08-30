<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        './src/',
        './tests/'
    ])
;

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'strict_param' => true,
        'single_line_throw' => false,
    ])
    ->setFinder($finder)
;
