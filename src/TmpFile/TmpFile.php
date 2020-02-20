<?php

namespace TmpFile;

final class TmpFile
{
    private $filename;

    public function __construct()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'php');

        if (!$this->filename) {
            throw new \RuntimeException('tempnam() could not create a temp file');
        }

        register_shutdown_function(function () {
            if (file_exists($this->filename)) {
                unlink($this->filename);
            }
        });
    }

    public function __toString(): string
    {
        return $this->filename;
    }
}