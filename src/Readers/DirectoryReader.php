<?php

declare(strict_types=1);

namespace Fi1a\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\Filesystem;
use Fi1a\Filesystem\FolderInterface;
use InvalidArgumentException;

/**
 * Чтение конфигов из директории
 */
class DirectoryReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $regex;

    /**
     * @var FolderInterface
     */
    private $folder;

    /**
     * @param string|FolderInterface $folder
     */
    public function __construct($folder, string $regex)
    {
        $this->regex = $regex;
        if (is_string($folder)) {
            try {
                $filesystem = new Filesystem(new LocalAdapter($folder));
            } catch (InvalidArgumentException $exception) {
                throw new ReaderException(
                    sprintf('Папка "%s" не найдена', htmlspecialchars($folder))
                );
            }
            $folder = $filesystem->factoryFolder($folder);
        }
        $this->folder = $folder;
    }

    /**
     * @inheritDoc
     */
    public function read()
    {
        $result = [];
        if (!$this->folder->canRead()) {
            throw new ReaderException(
                sprintf('Нет прав на чтение папки "%s"', htmlspecialchars($this->folder->getPath()))
            );
        }
        /**
         * @var FileInterface $file
         */
        foreach ($this->folder->allFiles() as $file) {
            if (preg_match($this->regex, $file->getName()) <= 0) {
                continue;
            }
            if (!$file->canRead()) {
                throw new ReaderException(
                    sprintf('Нет прав на чтение файла "%s"', htmlspecialchars($file->getPath()))
                );
            }
            $content = $file->read();
            if ($content) {
                $result[] = $content;
            }
        }

        return $result;
    }
}
