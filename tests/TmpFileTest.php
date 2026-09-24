<?php

declare(strict_types=1);

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;

final class TmpFileTest extends TestCase
{
    public function testConstructorCreatesFile(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile->getFilename());
    }

    public function testStringValueEqualsFilename(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists((string) $tmpFile);
        $this->assertSame($tmpFile->getFilename(), (string) $tmpFile);
    }

    public function testDestructorRemovesFile(): void
    {
        $filename = (static fn (): string => (new TmpFile())->getFilename())();

        $this->assertFileDoesNotExist($filename);
    }

    public function testFileCanBeUnlinkedWhileObjectIsAlive(): void
    {
        $tmpFile = new TmpFile();

        unlink($tmpFile->getFilename());

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }

    public function testRenamedFileRemainsAfterDestruction(): void
    {
        $tmpFile = new TmpFile();
        $filename = $tmpFile->getFilename();
        $destination = tempnam(sys_get_temp_dir(), 'php');

        if (false === $destination) {
            throw new \RuntimeException('Could not create a destination filename.');
        }

        unlink($destination);

        try {
            $this->assertTrue(rename($filename, $destination));
            unset($tmpFile);

            $this->assertFileDoesNotExist($filename);
            $this->assertFileExists($destination);
        } finally {
            unset($tmpFile);

            if (file_exists($destination)) {
                unlink($destination);
            }
        }
    }

    public function testDestructorReportsNoErrorAfterManualUnlink(): void
    {
        [$stdout, $stderr, $exitCode] = $this->runPhpInSubprocess(<<<'PHP'
$log = tempnam(sys_get_temp_dir(), 'php');
ini_set('error_log', $log);
$tmpFile = new \TmpFile\TmpFile();
unlink($tmpFile->getFilename());
unset($tmpFile);
echo file_get_contents($log);
unlink($log);
PHP);

        $this->assertSame(0, $exitCode, $stderr);
        $this->assertSame('', $stdout);
        $this->assertSame('', $stderr);
    }

    public function testShutdownHandlerRemovesFileWhileObjectIsReferenced(): void
    {
        [$stdout, $stderr, $exitCode] = $this->runPhpInSubprocess(<<<'PHP'
$tmpFile = new \TmpFile\TmpFile();
register_shutdown_function(static function () use ($tmpFile): void {
    echo file_exists($tmpFile->getFilename()) ? 'exists' : 'removed';
});
PHP);

        $this->assertSame(0, $exitCode, $stderr);
        $this->assertSame('', $stderr);
        $this->assertSame('removed', $stdout);
    }

    public function testShutdownHandlerPreservesFileCreatedAfterDestruction(): void
    {
        [$stdout, $stderr, $exitCode] = $this->runPhpInSubprocess(<<<'PHP'
$tmpFile = new \TmpFile\TmpFile();
$filename = $tmpFile->getFilename();
unset($tmpFile);
file_put_contents($filename, 'replacement');
echo $filename;
PHP);

        $this->assertSame(0, $exitCode, $stderr);
        $this->assertSame('', $stderr);

        try {
            $this->assertSame('replacement', file_get_contents($stdout));
        } finally {
            if (file_exists($stdout)) {
                unlink($stdout);
            }
        }
    }

    public function testCleanupLogsErrorWhenPathIsADirectory(): void
    {
        $tmpFile = new TmpFile();
        $filename = $tmpFile->getFilename();
        $log = tempnam(sys_get_temp_dir(), 'php');

        if (false === $log) {
            throw new \RuntimeException('Could not create a log file.');
        }

        $previousLog = ini_set('error_log', $log);

        if (false === $previousLog) {
            unlink($log);

            throw new \RuntimeException('Could not redirect the error log.');
        }

        try {
            $this->assertTrue(unlink($filename));
            $this->assertTrue(mkdir($filename));
            unset($tmpFile);

            $message = file_get_contents($log);

            if (false === $message) {
                throw new \RuntimeException('Could not read the error log.');
            }

            $this->assertStringContainsString(\sprintf('Couldn\'t remove temporary file "%s".', $filename), $message);
        } finally {
            unset($tmpFile);

            if (is_dir($filename)) {
                rmdir($filename);
            }

            ini_set('error_log', $previousLog);
            unlink($log);
        }
    }

    public function testInstancesCreateDistinctFilesAndCleanUpIndependently(): void
    {
        $first = new TmpFile();
        $second = new TmpFile();
        $firstFilename = $first->getFilename();
        $secondFilename = $second->getFilename();

        $this->assertNotSame($firstFilename, $secondFilename);

        unset($first);

        $this->assertFileDoesNotExist($firstFilename);
        $this->assertFileExists($secondFilename);

        unset($second);

        $this->assertFileDoesNotExist($secondFilename);
    }

    /** @return array{string, string, int} */
    private function runPhpInSubprocess(string $code): array
    {
        $autoload = var_export(\dirname(__DIR__).'/vendor/autoload.php', true);
        $process = proc_open(
            [\PHP_BINARY, '-r', \sprintf('require %s;%s%s', $autoload, "\n", $code)],
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
        );

        if (false === $process) {
            throw new \RuntimeException('Could not start a PHP subprocess.');
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        if (false === $stdout || false === $stderr) {
            throw new \RuntimeException('Could not read the PHP subprocess output.');
        }

        return [$stdout, $stderr, proc_close($process)];
    }
}
