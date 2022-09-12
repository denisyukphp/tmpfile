<?php

declare(strict_types=1);

namespace TmpFile\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\PhpProcess;
use TmpFile\TmpFile;

class TmpFileTest extends TestCase
{
    public function testCreateTmpFileAndReturnFilename(): void
    {
        $tmpFile = new TmpFile();
        $filenameA = (string) $tmpFile;
        $filenameB = $tmpFile->getFilename();

        $this->assertFileExists($filenameA);
        $this->assertFileExists($filenameB);
        $this->assertSame($filenameA, $filenameB);
    }

    public function testRemoveTmpFileOnGarbageCollection(): void
    {
        $filename = (function (): string {
            return (string) new TmpFile();
        })();

        $this->assertFileDoesNotExist($filename);
    }

    public function testRemoveTmpFileOnFatalError(): void
    {
        $fatalErrorUseCase = <<<'EOF'
<?php

require_once __DIR__.'/../vendor/autoload.php';

use TmpFile\TmpFile;

$tmpFile = new TmpFile();

echo $tmpFile->getFilename();

trigger_error('Fatal error!', \E_USER_ERROR);
EOF;

        $process = new PhpProcess($fatalErrorUseCase, __DIR__);
        $process->run();
        $processOutput = $process->getOutput();
        $data = explode(\PHP_EOL, $processOutput);

        $this->assertMatchesRegularExpression('~'.sys_get_temp_dir().'~', $data[0]);
        $this->assertFileDoesNotExist($data[0]);
    }
}
