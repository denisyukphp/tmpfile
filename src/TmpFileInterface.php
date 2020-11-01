<?php

namespace TmpFile;

interface TmpFileInterface
{
    /**
     * @return string Should return a temp file name on a disk
     */
    public function __toString();
}
