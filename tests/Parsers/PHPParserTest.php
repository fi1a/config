<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Parsers;

use Fi1a\Config\Parsers\PHPParser;
use PHPUnit\Framework\TestCase;

/**
 * Парсера PHP
 */
class PHPParserTest extends TestCase
{
    /**
     * Осуществляет декодирование переданной строки
     */
    public function testDecode(): void
    {
        $php = <<<'PHP'
<?php return [
    'foo' => [
        'bar' => 'baz',
    ],
    'qux' => 1,
];
PHP;

        $parser = new PHPParser();
        $this->assertEquals(
            [
                'foo' => [
                    'bar' => 'baz',
                ],
                'qux' => 1,
            ],
            $parser->decode($php)
        );
    }

    /**
     * Осуществляет кодирование переданной строки
     */
    public function testEncodeShortArraySyntax(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
        ];
        $php = <<<'PHP'
<?php

return [
    'foo' => [
        'bar' => 'baz',
    ],
    'qux' => 1,
];
PHP;

        $parser = new PHPParser();
        $this->assertEquals($php, $parser->encode($array));
    }

    /**
     * Осуществляет кодирование переданной строки
     */
    public function testEncodeNotShortArraySyntax(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
        ];
        $php = <<<'PHP'
<?php

return array(
    'foo' => array(
        'bar' => 'baz',
    ),
    'qux' => 1,
);
PHP;

        $parser = new PHPParser('UTF-8', false);
        $this->assertEquals($php, $parser->encode($array));
    }

    /**
     * Осуществляет кодирование переданной строки
     */
    public function testEncode1TabIndent(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
        ];
        $php = <<<'PHP'
<?php

return [
 'foo' => [
  'bar' => 'baz',
 ],
 'qux' => 1,
];
PHP;

        $parser = new PHPParser('UTF-8', true, '1tab');
        $this->assertEquals($php, $parser->encode($array));
    }

    /**
     * Осуществляет кодирование переданной строки
     */
    public function testEncodeCustomIndent(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
        ];
        $php = <<<'PHP'
<?php

return [
  'foo' => [
    'bar' => 'baz',
  ],
  'qux' => 1,
];
PHP;

        $parser = new PHPParser('UTF-8', true, '  ');
        $this->assertEquals($php, $parser->encode($array));
    }
}
