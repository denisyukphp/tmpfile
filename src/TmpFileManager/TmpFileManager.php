<?php

namespace TmpFileManager;

use TmpFile\TmpFile;
use TmpFileManager\DeferredPurgeHandler\DummyDeferredPurgeHandler;
use TmpFileManager\Exception\TmpFileIOException;
use TmpFileManager\Exception\TmpFileCreateException;

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
        $deferredPurgeHandler = $this->getConfig()->getDeferredPurgeHandler();

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
        $temporaryDirectory = $this->getConfig()->getTemporaryDirectory();
        $tmpFilePrefix = $this->getConfig()->getTmpFilePrefix();

        $fileName = $this->getTmpFileHandler()->getTmpFileName($temporaryDirectory, $tmpFilePrefix);

        try {
            $tmpFile = $this->makeTmpFile($fileName);
        } catch (\ReflectionException $e) {
            throw new TmpFileCreateException($e->getMessage());
        }

        $this->getContainer()->addTmpFile($tmpFile);

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
        if ($this->getContainer()->hasTmpFile($tmpFile)) {
            $this->getContainer()->removeTmpFile($tmpFile);
        }

        if ($this->getTmpFileHandler()->existsTmpFile($tmpFile)) {
            $this->getTmpFileHandler()->removeTmpFile($tmpFile);
        }
    }

    /**
     * @throws TmpFileIOException
     */
    public function purge(): void
    {
        if (0 >= $this->getContainer()->getTmpFilesCount()) {
            return;
        }

        $tmpFiles = $this->getContainer()->getTmpFiles();
        $checkUnclosedResources = $this->getConfig()->getCheckUnclosedResources();

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