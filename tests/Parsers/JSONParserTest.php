<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Parsers;

use Fi1a\Config\Exceptions\ParserException;
use Fi1a\Config\Parsers\JSONParser;
use PHPUnit\Framework\TestCase;

/**
 * Парсера JSON
 */
class JSONParserTest extends TestCase
{
    /**
     * Осуществляет декодирование переданной строки
     */
    public function testDecode(): void
    {
        $json = '{"foo":{"bar":"baz"},"qux":1}';
        $parser = new JSONParser();
        $this->assertEquals(
            [
                'foo' => [
                    'bar' => 'baz',
                ],
                'qux' => 1,
            ],
            $parser->decode($json)
        );
    }

    /**
     * Осуществляет декодирование переданной строки
     */
    public function testDecodeEmptyArray(): void
    {
        $json = 'true';
        $parser = new JSONParser();
        $this->assertEquals([], $parser->decode($json));
    }

    /**
     * Осуществляет декодирование переданной строки
     */
    public function testDecodeException(): void
    {
        $this->expectException(ParserException::class);
        $json = '';
        $parser = new JSONParser();
        $parser->decode($json);
    }

    /**
     * Осуществляет кодирование в строку
     */
    public function testEncode(): void
    {
        $json = '{"foo":{"bar":"baz"},"qux":1}';
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
        ];

        $parser = new JSONParser();
        $this->assertEquals($json, $parser->encode($array));
    }

    /**
     * Осуществляет кодирование в строку
     */
    public function testEncodeException(): void
    {
        $this->expectException(ParserException::class);
        $array = [1 => [2 => [3 => []]]];
        $parser = new JSONParser(2);
        $parser->encode($array);
    }
}
