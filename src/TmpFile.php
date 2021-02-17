<?php

namespace TmpFile;

final class TmpFile implements TmpFileInterface
{
    private $filename;

    public function __construct()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'php');

        if (false === $this->filename || !is_file($this->filename)) {
            throw new \RuntimeException("tempnam() couldn't create a temp file");
        }

        register_shutdown_function(function () {
            $this->__destruct();
        });
    }

    public function __toString(): string
    {
        return $this->filename;
    }

    public function __destruct()
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
    }
}
