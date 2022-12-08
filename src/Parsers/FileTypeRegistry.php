<?php

declare(strict_types=1);

namespace Fi1a\Config\Parsers;

use Fi1a\Config\Exceptions\InvalidArgumentException;

/**
 * Реестр парсеров по расширениям файлов
 */
class FileTypeRegistry implements FileTypeRegistryInterface
{
    /**
     * @var string[]
     */
    private static $extensions = [];

    /**
     * @inheritDoc
     */
    public static function add(string $extension, string $parser): bool
    {
        if (static::has($extension)) {
            return false;
        }
        if (!is_subclass_of($parser, ParserInterface::class)) {
            throw new InvalidArgumentException(
                sprintf('Класс должен реализовывать интерфейс %s', ParserInterface::class)
            );
        }

        static::$extensions[mb_strtolower($extension)] = $parser;

        return true;
    }

    /**
     * @inheritDoc
     */
    public static function has(string $extension): bool
    {
        return array_key_exists(mb_strtolower($extension), static::$extensions);
    }

    /**
     * @inheritDoc
     */
    public static function get(string $extension): string
    {
        if (!static::has($extension)) {
            throw new InvalidArgumentException(
                sprintf('Неизвестное расширение файла "%s"', $extension)
            );
        }

        return static::$extensions[mb_strtolower($extension)];
    }

    /**
     * @inheritDoc
     */
    public static function delete(string $extension): bool
    {
        if (!static::has($extension)) {
            return false;
        }

        unset(static::$extensions[mb_strtolower($extension)]);

        return true;
    }
}
