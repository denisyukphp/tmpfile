<?php

declare(strict_types=1);

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testCreateTmpFileWithReturningFilename(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile->getFilename());
    }

    public function testCreateTmpFileWithStringableBehavior(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists((string) $tmpFile);
    }

    public function testRemoveTmpFileOnGarbageCollection(): void
    {
        $filename = (new TmpFile())->getFilename();

        $this->assertFileDoesNotExist($filename);
    }

    public function testRemoveTmpFileThroughUnlink(): void
    {
        $tmpFile = new TmpFile();
        unlink($tmpFile->getFilename());

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
