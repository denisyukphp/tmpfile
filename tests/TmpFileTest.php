<?php

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpProcess;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testCreateTmpFile(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile);
    }

    public function testRemoveTmpFileOnGarbageCollection(): void
    {
        $callback = function () use (&$filename) {
            $filename = (string) new TmpFile();
        };

        $callback();

        $this->assertFileNotExists($filename);
    }

    public function testRemoveTmpFileOnFatalError(): void
    {
        $eof = $this->getFatalErrorUseCase();

        $process = new PhpProcess($eof, __DIR__);

        $process->run();

        $output = $process->getOutput();

        $data = explode(PHP_EOL, $output);

        $this->assertRegExp('~' . sys_get_temp_dir() . '~', $data[0]);
        $this->assertFileNotExists($data[0]);
    }

    private function getFatalErrorUseCase(): string
    {
        return <<<'EOF'
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TmpFile\TmpFile;

$tmpFile = new TmpFile();

echo (string) $tmpFile;

trigger_error('Fatal error!', E_USER_ERROR);
EOF;
    }
}
