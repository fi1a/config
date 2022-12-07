<?php

declare(strict_types=1);

namespace Fi1a\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\Filesystem;
use Fi1a\Filesystem\Utils\LocalUtil;

/**
 * Чтение конфига из файла
 */
class FileReader implements ReaderInterface
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
                throw new ReaderException(
                    sprintf('Файл "%s" не найден', htmlspecialchars($file))
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
