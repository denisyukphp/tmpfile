<?php

namespace TmpFile;

interface ContainerInterface
{
    public function addTmpFile(TmpFile $tmpFile): void;

    public function hasTmpFile(TmpFile $tmpFile): bool;

    public function removeTmpFile(TmpFile $tmpFile): void;

    /**
     * @return TmpFile[]
     */
    public function getTmpFiles(): array;

    public function getTmpFilesCount(): int;
}