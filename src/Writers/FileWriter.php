<?php

declare(strict_types=1);

namespace Fi1a\Config\Writers;

use Fi1a\Config\Exceptions\WriterException;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\Filesystem;
use Fi1a\Filesystem\Utils\LocalUtil;

/**
 * Запись конфигурации в файл
 */
class FileWriter implements WriterInterface
{
    /**
     * @var FileInterface
     */
    private $file;

    /**
     * @param string|FileInterface $file
     */
    public function __construct($file)
    {
        if (is_string($file)) {
            if (
                ($folderPath = LocalUtil::peekParentPath($file)) === false
                || ($folderRealPath = realpath($folderPath)) === false
            ) {
                throw new WriterException(
                    sprintf('Родительской папки у файла "%s" не существует', htmlspecialchars($file))
                );
            }
            $filesystem = new Filesystem(new LocalAdapter($folderRealPath));
            $info = pathinfo($file);
            $file = $filesystem->factoryFile($folderRealPath . '/' . $info['basename']);
        }
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function write(string $string): bool
    {
        if (!$this->file->canWrite()) {
            throw new WriterException(
                sprintf('Нет прав на запись файла "%s"', htmlspecialchars($this->file->getPath()))
            );
        }

        return is_numeric($this->file->write($string));
    }
}
