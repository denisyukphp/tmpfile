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

    public function testIsString()
    {
        settype($this->tmpFile, 'string');

        $this->assertIsString($this->tmpFile);
    }

    public function testFileExists()
    {
        $this->assertFileExists($this->tmpFile);
    }
}