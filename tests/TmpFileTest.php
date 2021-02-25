<?php

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testCreateTmpFile(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile);
    }

    public function testRemoveTmpFileOnGarbageCollection(): void
    {
        $callback = function () use (&$filename) {
            $filename = (string) new TmpFile();
        };

        $callback();

        $this->assertFileNotExists($filename);
    }
}
