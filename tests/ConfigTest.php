<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config;

use Fi1a\Config\Config;
use Fi1a\Config\ConfigValues;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Exceptions\InvalidArgumentException;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Readers\FileReader;
use Fi1a\Config\Writers\FileWriter;
use PHPUnit\Framework\TestCase;

/**
 * Конфигурации
 */
class ConfigTest extends TestCase
{
    /**
     * Загружает и возвращает значения конфигурации
     */
    public function testLoad(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
        ];
        $reader = new FileReader(__DIR__ . '/Fixtures/test.config.php');
        $parser = new PHPParser();
        $config = Config::load($reader, $parser);
        $this->assertInstanceOf(ConfigValuesInterface::class, $config);
        $this->assertEquals($array, $config->getArrayCopy());
    }

    /**
     * Загружает и возвращает значения конфигурации для нескольких конфигов
     *
     * @throws \Fi1a\Config\Exceptions\InvalidArgumentException
     */
    public function testBatchLoad(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
                'qur' => 'hax',
                'hux' => [1, 2, 3, 4, 5, 6, 7],
            ],
            'qux' => 2,
        ];
        $parser = new PHPParser();
        $config = Config::batchLoad([
            [
                new FileReader(__DIR__ . '/Fixtures/test.config.php'),
                $parser,
            ],
            [
                new FileReader(__DIR__ . '/Fixtures/test2.config.php'),
                $parser,
            ],
            [
                new FileReader(__DIR__ . '/Fixtures/test3.config.php'),
                $parser,
            ],
        ]);
        $this->assertInstanceOf(ConfigValuesInterface::class, $config);
        $this->assertEquals($array, $config->getArrayCopy());
    }

    /**
     * Загружает и возвращает значения конфигурации для нескольких конфигов (исключение при пустом классе для чтения)
     */
    public function testBatchLoadReaderException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $parser = new PHPParser();
        Config::batchLoad([
            [
                null,
                $parser,
            ],
            [
                new FileReader(__DIR__ . '/Fixtures/test2.config.php'),
                $parser,
            ],
        ]);
    }

    /**
     * Загружает и возвращает значения конфигурации для нескольких конфигов (исключение при пустом классе для чтения)
     */
    public function testBatchLoadParserException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $parser = new PHPParser();
        Config::batchLoad([
            [
                new FileReader(__DIR__ . '/Fixtures/test.config.php'),
                $parser,
            ],
            [
                new FileReader(__DIR__ . '/Fixtures/test2.config.php'),
                null,
            ],
        ]);
    }

    /**
     * Запись значений конфигурации
     */
    public function testWrite(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
        ];
        $values = new ConfigValues($array);
        $filePath = __DIR__ . '/Fixtures/write.php';
        $writer = new FileWriter($filePath);
        $reader = new FileReader($filePath);
        $parser = new PHPParser();
        Config::write($values, $parser, $writer);
        $this->assertTrue(is_file($filePath));
        $config = Config::load($reader, $parser);
        $this->assertInstanceOf(ConfigValuesInterface::class, $config);
        $this->assertEquals($array, $config->getArrayCopy());
        unlink($filePath);
    }
}
