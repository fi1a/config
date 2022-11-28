<?php

declare(strict_types=1);

namespace Fi1a\Config;

use Fi1a\Config\Parsers\ParserInterface;
use Fi1a\Config\Readers\ReaderInterface;
use Fi1a\Config\Writers\WriterInterface;

/**
 * Конфигурации
 */
interface ConfigInterface
{
    /**
     * Загрузка значений конфигурации
     */
    public function load(ReaderInterface $reader, ParserInterface $parser): ConfigValuesInterface;

    /**
     * Запист значений конфигурации
     */
    public function write(ConfigValuesInterface $values, WriterInterface $writer): bool;
}
