# TmpFile\TmpFile

[![Build Status](https://travis-ci.org/denisyukphp/tmpfile.svg?branch=master)](https://travis-ci.org/denisyukphp/tmpfile) [![Total Downloads](https://poser.pugx.org/denisyukphp/tmpfile/downloads)](https://packagist.org/packages/denisyukphp/tmpfile) [![License](https://poser.pugx.org/denisyukphp/tmpfile/license)](https://packagist.org/packages/denisyukphp/tmpfile)

```
composer require denisyukphp/tmpfile ^2
```

This package requires PHP 7.2 or later.

## Quick start

Класс *TmpFile* предназначен для работы с временным файлом на PHP как альтернатива стандартной функции `tmpfile()`. Временный файл автоматически удаляется после завершения скрипта.

```php
<?php

use TmpFile/TmpFile;

$tmpFile = new TmpFile();

$fh = fopen($tmpFile, 'r+');
fwrite($fh, 'Meow!');
fclose($fh);

$file = new \SplFileInfo($tmpFile);

rename($file->getRealPath(), '/path/to/meow.txt');
```

Вы можете использовать `new TmpFile()` независимо от менеджера.

## Temp files manager

Менеджер временных файлов позволяет получить больше контроля над временными файлами и может гарантировать их удаление. By default closing opened resources of temp files and automatically remove temp files are enabled, but you can immediately call method `purge()` of *TmpFileManager* to forced purge.

```php
<?php

use TmpFile/TmpFile;
use TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

for ($i = 0; $i < 100; $i++) {
    /** @var TmpFile $tmpFile */
    $tmpFile = $tmpFileManager->createTmpFile();

    $fh = fopen($tmpFile, 'r+');

    fwrite($fh, random_bytes(1024));

    // ...
}

$tmpFileManager->purge();
```

## Safe handling temp files

```php
<?php

use TmpFile/TmpFile;
use TmpFileManager\TmpFileManager;

$tmpFileManager = new TmpFileManager();

$tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
    file_put_contents($tmpFile, 'Meow!');

    $file = new \SplFileInfo($tmpFile);

    rename($file->getRealPath(), '/path/to/meow.txt');
});
```

## Advanced usage

You can construct *TmpFileManager* from interfaces and replace basic handlers to do more features to management temp files. Also you can use builder of config to advanced settings temp files manager.

```php
<?php

use TmpFileManager\Container;
use TmpFileManager\TmpFileHandler;
use TmpFileManager\ConfigBuilder;
use TmpFileManager\DeferredPurgeHandler\DefaultDeferredPurgeHandler;
use TmpFileManager\CloseOpenedResourcesHandler\DefaultCloseOpenedResourcesHandler;
use TmpFileManager\GarbageCollectionHandler\DefaultGarbageCollectionHandler;
use TmpFileManager\Config;
use TmpFileManager\TmpFileManager;
use Symfony\Component\Filesystem\Filesystem;

$container = new Container();
$tmpFileHandler = new TmpFileHandler(new Filesystem());
$configBuilder = new ConfigBuilder();

/** @var Config $config */
$config = $configBuilder
    ->setTemporaryDirectory('/tmp')
    ->setTmpFilePrefix('php')
    ->setDeferredPurgeHandler(new DefaultDeferredPurgeHandler())
    ->setCheckUnclosedResources(true)
    ->setCloseOpenedResourcesHandler(new DefaultCloseOpenedResourcesHandler())
    ->setGarageCollectionProbability(1)
    ->setGarbageCollectionDivisor(100)
    ->setGarbageCollectionLifetime(3600)
    ->setGarbageCollectionHandler(new DefaultGarbageCollectionHandler())
    ->build()
;

$tmpFileManager = new TmpFileManager($container, $tmpFileHandler, $config);
```
