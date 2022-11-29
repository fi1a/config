<?php

declare(strict_types=1);

namespace Fi1a\Config\Parsers;

use const PATHINFO_EXTENSION;

/**
 * Фабричный класс
 */
class Factory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function byFileType(string $filePath): ParserInterface
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $class = FileExtensionRegistry::get($ext);
        /** @psalm-suppress InvalidStringClass */
        $instance = new $class();
        assert($instance instanceof ParserInterface);

        return $instance;
    }
}
