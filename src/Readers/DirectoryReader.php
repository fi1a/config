<?php

declare(strict_types=1);

namespace Fi1a\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;

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
     * @var string
     */
    private $directoryPath;

    /**
     * Конструктор
     */
    public function __construct(string $directoryPath, string $regex)
    {
        $this->regex = $regex;
        $this->directoryPath = $directoryPath;
    }

    /**
     * @inheritDoc
     */
    public function read()
    {
        $result = [];
        if (!is_dir($this->directoryPath)) {
            throw new ReaderException(
                sprintf('Директория "%s" не найдена', $this->directoryPath)
            );
        }
        if (!is_readable($this->directoryPath)) {
            throw new ReaderException(
                sprintf('Нет прав на чтение директории "%s"', $this->directoryPath)
            );
        }
        foreach (scandir($this->directoryPath) as $entry) {
            $filePath = $this->directoryPath . '/' . $entry;
            if (
                $entry === '.'
                || $entry === '..'
                || !is_file($filePath)
                || preg_match($this->regex, $entry) <= 0
            ) {
                continue;
            }
            if (!is_readable($filePath)) {
                throw new ReaderException(sprintf('Нет прав на чтение файла "%s"', $filePath));
            }

            $result[] = file_get_contents($filePath);
        }

        return $result;
    }
}
