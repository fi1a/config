<?php

declare(strict_types=1);

namespace Fi1a\Config\Writers;

/**
 * Интерфейс записи конфигурации
 */
interface WriterInterface
{
    /**
     * Осуществляет запись
     */
    public function write(string $string): bool;
}
