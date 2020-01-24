<?php

namespace TmpFileManager;

use TmpFile\TmpFile;
use TmpFileManager\DeferredPurgeHandler\DummyDeferredPurgeHandler;
use TmpFileManager\Exception\TmpFileIOException;
use TmpFileManager\Exception\TmpFileCreateException;
use TmpFileManager\Exception\TmpFileContextCallbackException;

final class TmpFileManager
{
    private $container;

    private $tmpFileHandler;

    private $config;

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

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getTmpFileHandler(): TmpFileHandlerInterface
    {
        return $this->tmpFileHandler;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
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
    private function makeTmpFile(string $realPath): TmpFile
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
     * @return mixed
     *
     * @throws TmpFileIOException
     * @throws TmpFileCreateException
     * @throws TmpFileContextCallbackException
     */
    public function createTmpFileContext(callable $callback)
    {
        $tmpFile = $this->createTmpFile();

        try {
            $result = $callback($tmpFile);

            if ($result instanceof TmpFile) {
                throw new TmpFileContextCallbackException(
                    sprintf("You can't return %s object to context callback function", TmpFile::class)
                );
            }

            return $result;
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
        if (!$this->container->getTmpFilesCount()) {
            return;
        }

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