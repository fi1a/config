<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config;

use Fi1a\Config\Config;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Exceptions\InvalidArgumentException;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Readers\FileReader;
use Fi1a\Config\Writers\FileWriter;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * Конфигурации
 */
class ConfigTest extends TestCase
{
    /**
     * Возвращает файл
     */
    private function getFile(string $path): FileInterface
    {
        return (new Filesystem(new LocalAdapter(__DIR__ . '/Resources')))
            ->factoryFile($path);
    }

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
        $reader = new FileReader($this->getFile('./test.config.php'));
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
                new FileReader($this->getFile('./test.config.php')),
                $parser,
            ],
            [
                new FileReader($this->getFile('./test2.config.php')),
                $parser,
            ],
            [
                new FileReader($this->getFile('./test3.config.php')),
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
                new FileReader($this->getFile('./test2.config.php')),
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
                new FileReader($this->getFile('./test.config.php')),
                $parser,
            ],
            [
                new FileReader($this->getFile('./test2.config.php')),
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
        $values = Config::create($array);
        $file = $this->getFile('./write.php');
        $writer = new FileWriter($file);
        $reader = new FileReader($file);
        $parser = new PHPParser();
        Config::write($values, $parser, $writer);
        $this->assertTrue($file->isExist());
        $config = Config::load($reader, $parser);
        $this->assertInstanceOf(ConfigValuesInterface::class, $config);
        $this->assertEquals($array, $config->getArrayCopy());
        $file->delete();
    }
}
