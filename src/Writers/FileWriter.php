<?php

declare(strict_types=1);

namespace Fi1a\Config\Writers;

use Fi1a\Config\Exceptions\WriterException;

/**
 * Запись конфигурации в файл
 */
class FileWriter implements WriterInterface
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
    public function write(string $string): bool
    {
        if (
            (is_file($this->filePath) && !is_writable($this->filePath))
            || !is_writable(dirname($this->filePath))
        ) {
            throw new WriterException(sprintf('Нет прав на запись файла "%s"', $this->filePath));
        }

        return file_put_contents($this->filePath, $string) !== false;
    }
}
