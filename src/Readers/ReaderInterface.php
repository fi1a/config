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
     *
     * @return string[]|string
     */
    public function read();
}
