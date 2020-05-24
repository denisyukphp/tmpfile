<?php

use TmpFile\TmpFile;

class TmpFileTest extends \PHPUnit\Framework\TestCase
{
    /** @var TmpFile */
    private $tmpFile;

    public function setUp()
    {
        $this->tmpFile = new TmpFile();
    }

    public function testIsString(): string
    {
        settype($this->tmpFile, 'string');

        $this->assertIsString($this->tmpFile);

        return $this->tmpFile;
    }

    /**
     * @param string $tmpFile
     *
     * @depends testIsString
     */
    public function testFileExists(string $tmpFile): void
    {
        $this->assertFileExists($tmpFile);
    }
}