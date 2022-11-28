<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config;

use Fi1a\Config\Config;
use Fi1a\Config\ConfigValues;
use Fi1a\Config\ConfigValuesInterface;
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
