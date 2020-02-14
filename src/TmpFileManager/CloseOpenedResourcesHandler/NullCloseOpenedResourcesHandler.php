<?php

namespace TmpFileManager\CloseOpenedResourcesHandler;

use TmpFile\TmpFile;

class NullCloseOpenedResourcesHandler implements CloseOpenedResourcesHandlerInterface
{
    /**
     * @param TmpFile[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void
    {
    }
}