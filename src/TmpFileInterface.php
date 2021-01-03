<?php

namespace TmpFile;

interface TmpFileInterface
{
    public function getFilename(): string;

    public function __toString(): string;
}
