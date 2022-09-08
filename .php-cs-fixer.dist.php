<?php

$finder = (new PhpCsFixer\Finder())
    ->in('./src/')
;

return (new PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setCacheFile('./build/cache/.php-cs-fixer.cache')
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
    ])
    ->setFinder($finder)
;
