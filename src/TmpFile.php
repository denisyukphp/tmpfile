<?php

declare(strict_types=1);

namespace TmpFile;

final class TmpFile implements TmpFileInterface
{
    private string $filename;

    private \Closure $handler;

    public function __construct()
    {
        $this->filename = tempnam(sys_get_temp_dir(), 'php');

        if (false === $this->filename) {
            throw new \RuntimeException('tempnam() could not create a temp file');
        }

        $this->handler = static function (string $filename): void {
            if (file_exists($filename)) {
                unlink($filename);
            }
        };

        register_shutdown_function($this->handler, $this->filename);
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function __destruct()
    {
        ($this->handler)($this->filename);
    }
}
