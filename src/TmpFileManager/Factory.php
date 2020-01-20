<?php

namespace TmpFile\TmpFileManager;

use Symfony\Component\Filesystem\Filesystem;

class Factory
{
    public static function createFromDefault(?Filesystem $filesystem = null): TmpFileManager
    {
        if (!$filesystem) {
            $filesystem = new Filesystem();
        }

        $container = new Container();
        $tmpFileHandler = new TmpFileHandler($filesystem);
        $config = (new ConfigBuilder)->build();

        return new TmpFileManager($container, $tmpFileHandler, $config);
    }
}