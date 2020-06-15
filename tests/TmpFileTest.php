<?php

namespace Bulletproof\TmpFile\Tests;

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFile\TmpFileInterface;
use PHPUnit\Framework\TestCase;

class TmpFileTest extends TestCase
{
    /**
     * @var TmpFileInterface
     */
    private $tmpFile;

    public function setUp()
    {
        $this->tmpFile = new TmpFile();
    }

    /**
     * @return TmpFileInterface
     */
    public function testIsString(): TmpFileInterface
    {
        $filename = (string) $this->tmpFile;

        $this->assertIsString($filename);

        return $this->tmpFile;
    }

    /**
     * @depends testIsString
     *
     * @param TmpFileInterface $tmpFile
     *
     * @return TmpFile
     */
    public function testFileExists(TmpFileInterface $tmpFile): TmpFileInterface
    {
        $this->assertFileExists($tmpFile);

        return $tmpFile;
    }

    /**
     * @depends testFileExists
     *
     * @param TmpFileInterface $tmpFile
     */
    public function testUnlink(TmpFileInterface $tmpFile)
    {
        unlink($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}