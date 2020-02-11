<?php

namespace TmpFileManager;

use TmpFile\TmpFile;
use TmpFileManager\DeferredPurgeHandler\VoidDeferredPurgeHandler;
use Symfony\Component\Filesystem\Filesystem;

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
        ContainerInterface $container = null,
        TmpFileHandlerInterface $tmpFileHandler = null,
        ConfigInterface $config = null
    ) {
        $this->container = $container ?? new Container();
        $this->tmpFileHandler = $tmpFileHandler ?? new TmpFileHandler(new Filesystem);
        $this->config = $config ?? new Config(new ConfigBuilder);

        $this->initDeferredPurgeHandler();
        $this->initGarbageCollectionHandler();
    }

    private function initDeferredPurgeHandler(): void
    {
        $deferredPurgeHandler = $this->config->getDeferredPurgeHandler();

        if (!$deferredPurgeHandler instanceof VoidDeferredPurgeHandler) {
            $deferredPurgeHandler($this);
        }
    }

    private function initGarbageCollectionHandler(): void
    {
        $garbageCollectionHandler = $this->config->getGarbageCollectionHandler();

        if ($this->config->getGarbageCollectionProbability()) {
            $garbageCollectionHandler($this->config);
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
                    sprintf("You can't return %s object from context callback function", TmpFile::class)
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
        $tmpFilesCount = $this->container->getTmpFilesCount();
        $tmpFiles = $this->container->getTmpFiles();

        if (!$tmpFilesCount) {
            return;
        }

        $checkUnclosedResources = $this->config->getCheckUnclosedResources();
        $closeOpenedResourcesHandler = $this->config->getCloseOpenedResourcesHandler();

        if ($checkUnclosedResources) {
            $closeOpenedResourcesHandler($tmpFiles);
        }

        foreach ($tmpFiles as $tmpFile) {
            $this->removeTmpFile($tmpFile);
        }
    }
}