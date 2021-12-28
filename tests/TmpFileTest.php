<?php

declare(strict_types=1);

namespace TmpFile\Tests;

use TmpFile\TmpFile;
use Symfony\Component\Process\PhpProcess;
use PHPUnit\Framework\TestCase;

class TmpFileTest extends TestCase
{
    public function testCreateTmpFile(): void
    {
        $tmpFile = new TmpFile();

        $this->assertFileExists($tmpFile->getFilename());
    }

    public function testRemoveTmpFileOnGarbageCollection(): void
    {
        $callback = function () use (&$filename) {
            $filename = (string) new TmpFile();
        };

        $callback();

        $this->assertFileDoesNotExist($filename);
    }

    public function testRemoveTmpFileOnFatalError(): void
    {
        $process = new PhpProcess($this->getFatalErrorUseCase(), __DIR__);

        $process->run();

        $output = $process->getOutput();

        $data = explode(PHP_EOL, $output);

        $this->assertMatchesRegularExpression('~' . sys_get_temp_dir() . '~', $data[0]);

        $this->assertFileDoesNotExist($data[0]);
    }

    private function getFatalErrorUseCase(): string
    {
        return <<<'EOF'
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use TmpFile\TmpFile;

$tmpFile = new TmpFile();

echo $tmpFile->getFilename();

trigger_error('Fatal error!', E_USER_ERROR);
EOF;
    }
}
