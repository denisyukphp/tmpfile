<?php

namespace TmpFile;

final class TmpFile implements TmpFileInterface
{
    private $filename;

    public function __construct()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'php');

        if (!$this->filename) {
            throw new \RuntimeException("tempnam() couldn't create a temp file");
        }

        register_shutdown_function(function () {
            $this->__destruct();
        });
    }

    public function __destruct()
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
    }

    public function __toString()
    {
        return $this->filename;
    }
}
