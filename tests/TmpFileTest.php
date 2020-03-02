<?php

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;

class TmpFileTest extends TestCase
{
    public function testInit(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile);
    }
}