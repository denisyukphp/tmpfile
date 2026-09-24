<?php

declare(strict_types=1);

namespace TmpFile;

final readonly class TmpFile implements TmpFileInterface
{
    private string $filename;
    private \Closure $handler;

    public function __construct()
    {
        $filename = tempnam(sys_get_temp_dir(), 'php');

        if (false === $filename || '' === $filename) {
            throw new \RuntimeException('tempnam() couldn\'t create a temporary file.'); // @codeCoverageIgnore
        }

        $this->filename = $filename;

        $isRemoved = false;

        $this->handler = static function (string $filename) use (&$isRemoved): void {
            if ($isRemoved) {
                return; // @codeCoverageIgnore
            }

            $isRemoved = true;

            try {
                if (file_exists($filename) && !@unlink($filename)) {
                    throw new \RuntimeException(\sprintf('Couldn\'t remove temporary file "%s".', $filename));
                }
            } catch (\Throwable $e) {
                error_log($e->getMessage());
            }
        };

        register_shutdown_function($this->handler, $this->filename);
    }

    #[\Override]
    public function getFilename(): string
    {
        return $this->filename;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->filename;
    }

    public function __destruct()
    {
        ($this->handler)($this->filename);
    }
}
