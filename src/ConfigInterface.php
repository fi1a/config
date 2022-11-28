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
     * Загружает и возвращает значения конфигурации
     */
    public static function load(ReaderInterface $reader, ParserInterface $parser): ConfigValuesInterface;

    /**
     * Запись значений конфигурации
     */
    public static function write(ConfigValuesInterface $values, ParserInterface $parser, WriterInterface $writer): bool;
}
