<?php

declare(strict_types=1);

namespace TmpFile;

interface TmpFileInterface extends \Stringable
{
    public function getFilename(): string;
}
