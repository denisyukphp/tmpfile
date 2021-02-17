<?php

namespace TmpFile;

interface TmpFileInterface
{
    /**
     * @return string Should return the filename of a temp file on disk
     */
    public function __toString(): string;
}
