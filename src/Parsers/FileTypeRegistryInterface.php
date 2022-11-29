<?php

declare(strict_types=1);

namespace Fi1a\Config\Parsers;

/**
 * Реестр спиннеров
 */
interface FileTypeRegistryInterface
{
    /**
     * Добавить парсер ассоциированный с расширением файла
     */
    public static function add(string $extension, string $parser): bool;

    /**
     * Проверяет наличие парсера для указанного расширения
     */
    public static function has(string $extension): bool;

    /**
     * Возвращает парсер по расширению
     */
    public static function get(string $extension): string;

    /**
     * Удаляет парсер по расширению
     */
    public static function delete(string $extension): bool;
}
