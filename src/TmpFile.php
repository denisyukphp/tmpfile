<?php

declare(strict_types=1);

namespace TmpFile;

final class TmpFile implements TmpFileInterface
{
    private string $filename;
    private \Closure $handler;

    public function __construct()
    {
        $this->filename = $this->createTmpFile();

        $this->handler = static function (string $filename): void {
            if (file_exists($filename)) {
                unlink($filename);
            }
        };

        register_shutdown_function($this->handler, $this->filename);
    }

    /**
     * @codeCoverageIgnore
     */
    private function createTmpFile(): string
    {
        $filename = tempnam(sys_get_temp_dir(), 'php');

        if (false === $filename) {
            throw new \RuntimeException("tempnam() couldn't create a temp file.");
        }

        return $filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function __toString(): string
    {
        return $this->filename;
    }

    public function __destruct()
    {
        ($this->handler)($this->filename);
    }
}
