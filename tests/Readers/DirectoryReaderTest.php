<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Config\Readers\DirectoryReader;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\Filesystem;
use Fi1a\Filesystem\FolderInterface;
use PHPUnit\Framework\TestCase;

/**
 * Чтение конфигов из директории
 */
class DirectoryReaderTest extends TestCase
{
    /**
     * Возвращает папку
     */
    private function getFolder(): FolderInterface
    {
        return (new Filesystem(new LocalAdapter(__DIR__ . '/../Resources')))
            ->factoryFolder(realpath(__DIR__ . '/../Resources'));
    }

    /**
     * Осуществляет чтение
     */
    public function testRead(): void
    {
        $reader = new DirectoryReader($this->getFolder(), '/(.)+\.php/');
        $result = $reader->read();
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
    }

    /**
     * Осуществляет чтение
     */
    public function testReadSkipByPattern(): void
    {
        $reader = new DirectoryReader($this->getFolder(), '/(.)+\.txt/');
        $result = $reader->read();
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /**
     * Осуществляет чтение (исключение при отсутствии директории)
     */
    public function testReadDirectoryNotFound(): void
    {
        $this->expectException(ReaderException::class);
        $folder = $this->getFolder()->getFolder('not-found');
        $reader = new DirectoryReader($folder, '/(.)+\.php/');
        $reader->read();
    }

    /**
     * Осуществляет чтение (исключение при отсутствии прав на чтение директории)
     */
    public function testReadDirectoryNotAccess(): void
    {
        $this->expectException(ReaderException::class);
        $directory = $this->getFolder()->getPath();
        chmod($directory, 0000);
        $reader = new DirectoryReader($this->getFolder(), '/(.)+\.php/');
        try {
            $reader->read();
        } catch (ReaderException $exception) {
            chmod($directory, 0775);

            throw $exception;
        }
    }

    /**
     * Осуществляет чтение (исключение при отсутствии прав на чтение директории)
     */
    public function testReadFileNotAccess(): void
    {
        $this->expectException(ReaderException::class);
        $directory = $this->getFolder()->getPath();
        chmod($directory . '/test.config.php', 0000);
        $reader = new DirectoryReader($this->getFolder(), '/(.)+\.php/');
        try {
            $reader->read();
        } catch (ReaderException $exception) {
            chmod($directory . '/test.config.php', 0775);

            throw $exception;
        }
    }
}
