<?php

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;

class TmpFileTest extends TestCase
{
    /**
     * @var TmpFile
     */
    private $tmpFile;

    public function setUp()
    {
        $this->tmpFile = new TmpFile();
    }

    /**
     * @return TmpFile
     */
    public function testIsString(): TmpFile
    {
        $filename = (string) $this->tmpFile;

        $this->assertIsString($filename);

        return $this->tmpFile;
    }

    /**
     * @depends testIsString
     *
     * @param TmpFile $tmpFile
     *
     * @return TmpFile
     */
    public function testFileExists(TmpFile $tmpFile): TmpFile
    {
        $this->assertFileExists($tmpFile);

        return $tmpFile;
    }

    /**
     * @depends testFileExists
     *
     * @param TmpFile $tmpFile
     */
    public function testUnlink(TmpFile $tmpFile)
    {
        unlink($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}