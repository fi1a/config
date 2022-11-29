<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Parsers;

use Fi1a\Config\Parsers\Factory;
use Fi1a\Config\Parsers\JSONParser;
use Fi1a\Config\Parsers\PHPParser;
use PHPUnit\Framework\TestCase;

/**
 * Фабричный класс
 */
class FactoryTest extends TestCase
{
    /**
     * Фабричный метод создающий парсер на основе расширения файла
     */
    public function testByFileTypePHP(): void
    {
        $parser = Factory::byFileType(__DIR__ . '/../Fixtures/test.config.php');
        $this->assertInstanceOf(PHPParser::class, $parser);
    }

    /**
     * Фабричный метод создающий парсер на основе расширения файла
     */
    public function testByFileTypeJSON(): void
    {
        $parser = Factory::byFileType(__DIR__ . '/../Fixtures/test.config.json');
        $this->assertInstanceOf(JSONParser::class, $parser);
    }
}
