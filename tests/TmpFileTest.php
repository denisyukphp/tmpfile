<?php

namespace Bulletproof\TmpFile\Tests;

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFile\TmpFileInterface;
use PHPUnit\Framework\TestCase;

class TmpFileTest extends TestCase
{
    /**
     * @return TmpFileInterface
     */
    public function testIsString(): TmpFileInterface
    {
        $tmpFile = new TmpFile();

        $filename = (string) $tmpFile;

        $this->assertIsString($filename);

        return $tmpFile;
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