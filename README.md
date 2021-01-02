# TmpFile

[![Build Status](https://travis-ci.org/denisyukphp/tmpfile.svg?branch=master)](https://travis-ci.org/denisyukphp/tmpfile) [![Total Downloads](https://poser.pugx.org/denisyukphp/tmpfile/downloads)](https://packagist.org/packages/denisyukphp/tmpfile) [![License](https://poser.pugx.org/denisyukphp/tmpfile/license)](https://packagist.org/packages/denisyukphp/tmpfile)

```
composer require denisyukphp/tmpfile
```

This package requires PHP 7.1 or later.

## Quick usage

```php
<?php

use TmpFile\TmpFile;

$tmpFile = new TmpFile();

file_put_contents($tmpFile, 'Meow!');

rename($tmpFile, '/path/to/meow.txt');
```

Read more about temp file on [Habr](https://habr.com/ru/post/320078/).
