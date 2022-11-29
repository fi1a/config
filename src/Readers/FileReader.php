<?php

declare(strict_types=1);

namespace Fi1a\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;

/**
 * Чтение конфига из файла
 */
class FileReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * Конструктор
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @inheritDoc
     */
    public function read()
    {
        if (!is_file($this->filePath)) {
            throw new ReaderException(sprintf('Файл "%s" не найден', $this->filePath));
        }
        if (!is_readable($this->filePath)) {
            throw new ReaderException(sprintf('Нет прав на чтение файла "%s"', $this->filePath));
        }

        return file_get_contents($this->filePath);
    }
}
