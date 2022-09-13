<?php

declare(strict_types=1);

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testCreateTmpFileAndReturnFilename(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile->getFilename());
    }

    public function testCreateTmpFileAndStringableBehavior(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists((string) $tmpFile);
    }

    public function testRemoveTmpFileOnGarbageCollection(): void
    {
        $filename = (function (): string {
            return (new TmpFile())->getFilename();
        })();

        $this->assertFileDoesNotExist($filename);
    }
}
