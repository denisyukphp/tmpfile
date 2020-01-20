<?php

namespace TmpFile\TmpFileManager;

use TmpFile\TmpFile;
use TmpFile\TmpFileManager\Exception\TmpFileIOException;

interface TmpFileHandlerInterface
{
    /**
     * @param string $temporaryDirectory
     * @param string $fileNamePrefix
     *
     * @return string
     *
     * @throws TmpFileIOException
     */
    public function getTmpFileName(string $temporaryDirectory, string $fileNamePrefix): string;

    /**
     * @param TmpFile $tmpFile
     *
     * @return bool
     *
     * @throws TmpFileIOException
     */
    public function existsTmpFile(TmpFile $tmpFile): bool;

    /**
     * @param TmpFile $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFile $tmpFile): void;
}