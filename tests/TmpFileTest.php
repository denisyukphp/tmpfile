<?php

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;

class TmpFileTest extends TestCase
{
    /** @var TmpFile */
    private $tmpFile;

    public function setUp()
    {
        $this->tmpFile = new TmpFile();
    }

    public function testFileExists()
    {
        $this->assertFileExists($this->tmpFile);
    }

    public function testUnlink()
    {
        unlink($this->tmpFile);

        $this->assertFileNotExists($this->tmpFile);
    }

    public function testIsString()
    {
        settype($this->tmpFile, 'string');

        $this->assertIsString($this->tmpFile);
    }
}