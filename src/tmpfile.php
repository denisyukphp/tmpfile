<?php

namespace tmpfile;

final class tmpfile
{
    private $filename;

    public function __construct()
    {
        $this->filename = \tempnam(\sys_get_temp_dir(), 'php');

        if (!$this->filename) {
            throw new \RuntimeException('The function tempnam() could not create a file in temporary directory');
        }

        \register_shutdown_function(function (): void {
            if (\file_exists($this->filename)) {
                \unlink($this->filename);
            }
        });
    }

    public function __toString(): string
    {
        return \realpath($this->filename);
    }
}