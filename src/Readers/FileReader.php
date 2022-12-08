<?php

declare(strict_types=1);

namespace Fi1a\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Filesystem\FileInterface;

/**
 * Чтение конфига из файла
 */
class FileReader implements ReaderInterface
{
    /**
     * @var FileInterface
     */
    private $file;

    public function __construct(FileInterface $file)
    {
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function read()
    {
        if (!$this->file->isExist()) {
            throw new ReaderException(
                sprintf('Файл "%s" не найден', htmlspecialchars($this->file->getPath()))
            );
        }
        if (!$this->file->canRead()) {
            throw new ReaderException(
                sprintf('Нет прав на чтение файла "%s"', htmlspecialchars($this->file->getPath()))
            );
        }
        $content = $this->file->read();

        return $content !== false ? $content : '';
    }
}
