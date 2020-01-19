<?php

namespace TmpFile;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use TmpFile\Exception\TmpFileIOException;

class TmpFileHandler implements TmpFileHandlerInterface
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $temporaryDirectory
     * @param string $fileNamePrefix
     *
     * @return string
     *
     * @throws TmpFileIOException
     */
    public function getTmpFileName(string $temporaryDirectory, string $fileNamePrefix): string
    {
        try {
            return $this->filesystem->tempnam($temporaryDirectory, $fileNamePrefix);
        } catch (IOException $e) {
            throw new TmpFileIOException(
                $e->getMessage()
            );
        }
    }

    /**
     * @param TmpFile $tmpFile
     *
     * @return bool
     *
     * @throws TmpFileIOException
     */
    public function existsTmpFile(TmpFile $tmpFile): bool
    {
        try {
            return $this->filesystem->exists($tmpFile);
        } catch (IOException $e) {
            throw new TmpFileIOException(
                $e->getMessage()
            );
        }
    }

    /**
     * @param TmpFile $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFile $tmpFile): void
    {
        try {
            $this->filesystem->remove($tmpFile);
        } catch (IOException $e) {
            throw new TmpFileIOException(
                $e->getMessage()
            );
        }
    }
}