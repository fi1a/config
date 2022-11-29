<?php

declare(strict_types=1);

namespace Fi1a\Config\Parsers;

/**
 * Фабричный класс
 */
interface FactoryInterface
{
    /**
     * Фабричный метод создающий парсер на основе расширения файла
     */
    public static function byFileType(string $filePath): ParserInterface;
}
