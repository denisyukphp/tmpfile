<?php

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testGetFilename(): void
    {
        $tmpFile = new TmpFile();

        $filename = $tmpFile->getFilename();

        $this->assertFileExists($filename);
    }

    public function testToString(): void
    {
        $tmpFile = new TmpFile();

        $filename = $tmpFile->__toString();

        $this->assertNotEmpty($filename);
    }

    public function testFileExists(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile);
    }

    public function testUnlink(): void
    {
        $tmpFile = new TmpFile();

        unlink($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}
