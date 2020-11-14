<?php

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testIsString()
    {
        $tmpFile = new TmpFile();

        $filename = (string) $tmpFile;

        $this->assertIsString($filename);
    }

    public function testFileExists()
    {
        $tmpFile = new TmpFile();

        $this->assertTrue(file_exists($tmpFile));
    }

    public function testUnlink()
    {
        $tmpFile = new TmpFile();

        unlink($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}
