<?php

namespace TmpFileManager\CloseOpenedResourcesHandler;

use TmpFile\TmpFile;

class DefaultCloseOpenedResourcesHandler implements CloseOpenedResourcesHandlerInterface
{
    /**
     * @param TmpFile[] $tmpFiles
     */
    public function __invoke(array $tmpFiles): void
    {
        foreach (get_resources('stream') as $resource) {
            if (!stream_is_local($resource) ) {
                continue;
            }

            $metadata = stream_get_meta_data($resource);

            if (in_array($metadata['uri'], $tmpFiles)) {
                fclose($resource);
            }
        }
    }
}