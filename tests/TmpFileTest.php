<?php

declare(strict_types=1);

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

final class TmpFileTest extends TestCase
{
    public function testCreateTmpFile(): void
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
        $filename = (static function (): string {
            return (new TmpFile())->getFilename();
        })();

        $this->assertFileDoesNotExist($filename);
    }

    public function testRemoveTmpFileViaUnlink(): void
    {
        $tmpFile = new TmpFile();

        unlink($tmpFile->getFilename());

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
