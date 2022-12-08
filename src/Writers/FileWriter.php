<?php

declare(strict_types=1);

namespace Fi1a\Config\Writers;

use Fi1a\Config\Exceptions\WriterException;
use Fi1a\Filesystem\FileInterface;

/**
 * Запись конфигурации в файл
 */
class FileWriter implements WriterInterface
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
    public function write(string $string): bool
    {
        $folder = $this->file->getParent();
        if ($folder && !$folder->isExist()) {
            throw new WriterException(
                sprintf(
                    'Папка "%s" не существует',
                    htmlspecialchars($folder->getPath())
                )
            );
        }
        if (!$this->file->canWrite()) {
            throw new WriterException(
                sprintf('Нет прав на запись файла "%s"', htmlspecialchars($this->file->getPath()))
            );
        }

        return is_numeric($this->file->write($string));
    }
}
