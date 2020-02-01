<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\ConfigInterface;
use Symfony\Component\Process\Process;

class DefaultGarbageCollectionHandler implements GarbageCollectionHandlerInterface
{
    public function __invoke(ConfigInterface $config): void
    {
        $dir = $config->getTemporaryDirectory();
        $prefix = $config->getTmpFilePrefix();
        $probability = $config->getGarbageCollectionProbability();
        $divisor = $config->getGarbageCollectionDivisor();
        $lifetime = $config->getGarbageCollectionLifetime();

        if ($this->isChance($probability, $divisor)) {
            $this->process($dir, $prefix, $lifetime);
        }
    }

    private function isChance(int $probability, int $divisor): bool
    {
        return $probability == rand($probability, $divisor);
    }

    private function process(string $dir, string $prefix, int $lifetime): void
    {
        $minutes = $this->convertSecondsToMinutes($lifetime);

        $process = new Process([
            'find', $dir,
            '-name', ($prefix . '*'),
            '-type', 'f',
            '-amin', ('+' . $minutes),
            '-maxdepth', 1,
            '-delete',
        ]);

        $process->start();
    }

    private function convertSecondsToMinutes(int $seconds): int
    {
        return ceil($seconds / 60);
    }
}