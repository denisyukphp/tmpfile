<?php

namespace TmpFileManager;

use TmpFile\TmpFile;

class Container implements ContainerInterface
{
    private $tmpFiles;

    public function __construct()
    {
        $this->tmpFiles = new \SplObjectStorage();
    }

    public function addTmpFile(TmpFile $tmpFile): void
    {
        $this->tmpFiles->attach($tmpFile);
    }

    public function hasTmpFile(TmpFile $tmpFile): bool
    {
        return $this->tmpFiles->contains($tmpFile);
    }

    public function removeTmpFile(TmpFile $tmpFile): void
    {
        $this->tmpFiles->detach($tmpFile);
    }

    /**
     * @return TmpFile[]
     */
    public function getTmpFiles(): array
    {
        return iterator_to_array($this->tmpFiles, false);
    }

    public function getTmpFilesCount(): int
    {
        return $this->tmpFiles->count();
    }
}