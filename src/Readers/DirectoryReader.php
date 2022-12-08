<?php

declare(strict_types=1);

namespace Fi1a\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\FolderInterface;

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

    public function __construct(FolderInterface $folder, string $regex)
    {
        $this->regex = $regex;
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
