<?php

namespace TmpFileManager\CloseOpenedResourcesHandler;

use TmpFile\TmpFile;

class VoidCloseOpenedResourcesHandler implements CloseOpenedResourcesHandlerInterface
{
    /**
     * @param TmpFile[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void
    {
    }
}