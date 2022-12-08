<?php

declare(strict_types=1);

namespace Fi1a\Config;

use Fi1a\Config\Exceptions\InvalidArgumentException;
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
        return self::create(self::doLoad($reader, $parser));
    }

    /**
     * @inheritDoc
     */
    public static function batchLoad(array $batch): ConfigValuesInterface
    {
        $config = [];
        foreach ($batch as $item) {
            [$reader, $parser] = $item;
            if (!$reader instanceof ReaderInterface) {
                throw new InvalidArgumentException('Не передан класс для чтения конфигурации');
            }
            if (!$parser instanceof ParserInterface) {
                throw new InvalidArgumentException('Не передан класс для парсинга конфигурации');
            }

            $config = self::mergeConfig($config, self::doLoad($reader, $parser));
        }

        return self::create($config);
    }

    /**
     * Выполняет загрузку значений конфигурации
     *
     * @return mixed[]
     */
    private static function doLoad(ReaderInterface $reader, ParserInterface $parser): array
    {
        $config = [];

        $strings = (array) $reader->read();
        foreach ($strings as $string) {
            $config = self::mergeConfig($config, $parser->decode($string));
        }

        return $config;
    }

    /**
     * @inheritDoc
     */
    public static function write(ConfigValuesInterface $values, ParserInterface $parser, WriterInterface $writer): bool
    {
        return $writer->write($parser->encode($values->getArrayCopy()));
    }

    /**
     * @inheritDoc
     */
    public static function create(array $values = []): ConfigValuesInterface
    {
        return new ConfigValues($values);
    }

    /**
     * Рекурсивно объединяет массивы
     *
     * @param mixed[] $source
     * @param mixed[] $replace
     *
     * @return mixed[]
     *
     * @psalm-suppress MixedAssignment
     */
    protected static function mergeConfig(array $source, array $replace): array
    {
        foreach ($replace as $key => $value) {
            if (is_array($value) && array_key_exists($key, $source) && is_array($source[$key])) {
                $source[$key] = static::mergeConfig($source[$key], $value);

                continue;
            }
            if (is_numeric($key)) {
                $source[] = $value;

                continue;
            }

            $source[$key] = $value;
        }

        return $source;
    }
}
