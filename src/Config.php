<?php

declare(strict_types=1);

namespace Fi1a\Config;

use Fi1a\Config\Parsers\ParserInterface;
use Fi1a\Config\Readers\ReaderInterface;
use Fi1a\Config\Writers\WriterInterface;

/**
 * Конфигурации
 */
class Config implements ConfigInterface
{
    /**
     * @inheritDoc
     */
    public static function load(ReaderInterface $reader, ParserInterface $parser): ConfigValuesInterface
    {
        return new ConfigValues($parser->decode($reader->read()));
    }

    /**
     * @inheritDoc
     */
    public static function write(ConfigValuesInterface $values, ParserInterface $parser, WriterInterface $writer): bool
    {
        return $writer->write($parser->encode($values->getArrayCopy()));
    }
}
