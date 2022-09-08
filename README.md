# TmpFile

[![Build Status](https://img.shields.io/github/workflow/status/denisyukphp/tmpfile/build/master?style=plastic)](https://github.com/denisyukphp/tmpfile/actions/workflows/ci.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/denisyukphp/tmpfile?style=plastic)](https://packagist.org/packages/denisyukphp/tmpfile)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/denisyukphp/tmpfile?style=plastic&color=8892BF)](https://packagist.org/packages/denisyukphp/tmpfile)
[![Total Downloads](https://img.shields.io/packagist/dt/denisyukphp/tmpfile?style=plastic)](https://packagist.org/packages/denisyukphp/tmpfile)
[![License](https://img.shields.io/packagist/l/denisyukphp/tmpfile?style=plastic&color=428F7E)](https://packagist.org/packages/denisyukphp/tmpfile)

Alternative to tmpfile() function.

## Installation

You can install the latest version via [Composer](https://getcomposer.org/):

```text
composer require denisyukphp/tmpfile
```

This package requires PHP 8.0 or later.

## Quick usage

A temp file will be removed after PHP finished:

```php
<?php

use TmpFile\TmpFile;

$tmpFile = new TmpFile();

file_put_contents($tmpFile, 'Meow!');

rename($tmpFile, '/path/to/meow.txt');
```

Read more about temp file on [Habr](https://habr.com/ru/post/320078/).
