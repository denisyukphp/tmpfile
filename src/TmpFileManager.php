<?php

namespace TmpFile;

use TmpFile\DeferredPurgeHandler\DummyDeferredPurgeHandler;
use TmpFile\Exception\TmpFileIOException;
use TmpFile\Exception\TmpFileCreateException;

final class TmpFileManager
{
    /**
     * @var ContainerInterface $container
     * @var TmpFileHandlerInterface $tmpFileHandler
     * @var ConfigInterface $config
     */
    private
        $container,
        $tmpFileHandler,
        $config
    ;

    public function __construct(
        ContainerInterface $container,
        TmpFileHandlerInterface $tmpFileHandler,
        ConfigInterface $config
    ) {
        $this->container = $container;
        $this->tmpFileHandler = $tmpFileHandler;
        $this->config = $config;

        $this->initDeferredPurgeHandler();
    }

    private function initDeferredPurgeHandler(): void
    {
        $deferredPurgeHandler = $this->config->getDeferredPurgeHandler();

        if (!$deferredPurgeHandler instanceof DummyDeferredPurgeHandler) {
            $deferredPurgeHandler($this);
        }
    }

    /**
     * @return TmpFile
     *
     * @throws TmpFileIOException
     * @throws TmpFileCreateException
     */
    public function createTmpFile(): TmpFile
    {
        $temporaryDirectory = $this->config->getTemporaryDirectory();
        $tmpFilePrefix = $this->config->getTmpFilePrefix();

        $fileName = $this->tmpFileHandler->getTmpFileName($temporaryDirectory, $tmpFilePrefix);

        try {
            $tmpFile = $this->makeTmpFile($fileName);
        } catch (\ReflectionException $e) {
            throw new TmpFileCreateException(
                $e->getMessage()
            );
        }

        $this->container->addTmpFile($tmpFile);

        return $tmpFile;
    }

    /**
     * @param string $realPath
     *
     * @return TmpFile
     *
     * @throws \ReflectionException
     */
    protected function makeTmpFile(string $realPath): TmpFile
    {
        $tmpFileReflection = new \ReflectionClass(TmpFile::class);

        /** @var TmpFile $tmpFile */
        $tmpFile = $tmpFileReflection->newInstanceWithoutConstructor();

        $fileName = $tmpFileReflection->getProperty('fileName');

        $fileName->setAccessible(true);

        $fileName->setValue($tmpFile, $realPath);

        return $tmpFile;
    }

    /**
     * @param callable $callback
     *
     * @throws TmpFileIOException
     * @throws TmpFileCreateException
     */
    public function createTmpFileContext(callable $callback): void
    {
        $tmpFile = $this->createTmpFile();

        try {
            $callback($tmpFile);
        } finally {
            $this->removeTmpFile($tmpFile);
        }
    }

    /**
     * @param TmpFile $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFile $tmpFile): void
    {
        if ($this->container->hasTmpFile($tmpFile)) {
            $this->container->removeTmpFile($tmpFile);
        }

        if ($this->tmpFileHandler->existsTmpFile($tmpFile)) {
            $this->tmpFileHandler->removeTmpFile($tmpFile);
        }
    }

    /**
     * @throws TmpFileIOException
     */
    public function purge(): void
    {
        $tmpFiles = $this->container->getTmpFiles();
        $checkUnclosedResources = $this->config->getCheckUnclosedResources();

        if ($checkUnclosedResources) {
            $this->closeOpenedResources($tmpFiles);
        }

        foreach ($tmpFiles as $tmpFile) {
            $this->removeTmpFile($tmpFile);
        }
    }

    /**
     * @param TmpFile[] $tmpFiles
     */
    private function closeOpenedResources(array $tmpFiles): void
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