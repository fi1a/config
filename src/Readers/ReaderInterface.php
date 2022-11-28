<?php

declare(strict_types=1);

namespace Fi1a\Config\Readers;

/**
 * Интерфейс класса для чтения конфигурации
 */
interface ReaderInterface
{
    /**
     * Осуществляет чтение
     */
    public function read(): string;
}
