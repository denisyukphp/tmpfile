<?php

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testInit(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile);
    }
}