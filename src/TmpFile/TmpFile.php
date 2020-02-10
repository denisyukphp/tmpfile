<?php

namespace TmpFile;

final class TmpFile
{
    private $fileName;

    public function __construct()
    {
        $this->fileName = tempnam(sys_get_temp_dir(), 'php');

        if (!$this->fileName) {
            throw new \RuntimeException('The function tempnam() could not create a temp file');
        }

        register_shutdown_function(function () {
            if (file_exists($this->fileName)) {
                unlink($this->fileName);
            }
        });
    }

    public function __toString(): string
    {
        return $this->fileName;
    }
}