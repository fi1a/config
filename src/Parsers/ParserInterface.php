<?php

declare(strict_types=1);

namespace Fi1a\Config\Parsers;

/**
 * Интерфейс парсера
 */
interface ParserInterface
{
    /**
     * Осуществляет декодирование переданной строки
     *
     * @return mixed[]
     */
    public function decode(string $string): array;

    /**
     * Осуществляет кодирование в строку
     *
     * @param mixed[] $values
     */
    public function encode(array $values): string;
}
