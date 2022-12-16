<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Parsers;

use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Unit\Config\Fixtures\SetStateA;
use Fi1a\Unit\Config\Fixtures\SetStateC;
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
     * Осуществляет кодирование в строку
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
     * Осуществляет кодирование в строку
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
     * Осуществляет кодирование в строку
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
     * Осуществляет кодирование в строку
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

    /**
     * Осуществляет кодирование в строку
     */
    public function testEncodeWithParenthesesShortArraySyntax(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
            'cls' => new SetStateA(),
        ];
        $php = <<<'PHP'
<?php

return [
    'foo' => [
        'bar' => 'baz',
    ],
    'qux' => 1,
    'cls' => Fi1a\Unit\Config\Fixtures\SetStateA::__set_state([
        'foo' => 'bar',
        'baz' => Fi1a\Unit\Config\Fixtures\SetStateB::__set_state([
            'qux' => 'quz',
        ]),
    ]),
];
PHP;

        $parser = new PHPParser('UTF-8', true);
        $this->assertEquals($php, $parser->encode($array));
    }

    /**
     * Осуществляет кодирование в строку
     */
    public function testEncodeWithParenthesesSyntax(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
            'cls' => new SetStateA(),
        ];
        $php = <<<'PHP'
<?php

return array(
    'foo' => array(
        'bar' => 'baz',
    ),
    'qux' => 1,
    'cls' => Fi1a\Unit\Config\Fixtures\SetStateA::__set_state(array(
        'foo' => 'bar',
        'baz' => Fi1a\Unit\Config\Fixtures\SetStateB::__set_state(array(
            'qux' => 'quz',
        )),
    )),
);
PHP;

        $parser = new PHPParser('UTF-8', false);
        $this->assertEquals($php, $parser->encode($array));
    }

    /**
     * Осуществляет кодирование в строку
     */
    public function testEncodeWithEmptyParenthesesSyntax(): void
    {
        $array = [
            'foo' => [
                'bar' => 'baz',
            ],
            'qux' => 1,
            'cls' => new SetStateC(),
        ];
        $php = <<<'PHP'
<?php

return array(
    'foo' => array(
        'bar' => 'baz',
    ),
    'qux' => 1,
    'cls' => Fi1a\Unit\Config\Fixtures\SetStateC::__set_state(array()),
);
PHP;

        $parser = new PHPParser('UTF-8', false);
        $this->assertEquals($php, $parser->encode($array));
    }

    /**
     * Осуществляет кодирование в строку
     */
    public function testEncodeWithIntArray(): void
    {
        $array = [
            'foo' => [1, 2, 3],
            'bar' => new SetStateA(),
        ];
        $php = <<<'PHP'
<?php

return [
    'foo' => [
        0 => 1,
        1 => 2,
        2 => 3,
    ],
    'bar' => Fi1a\Unit\Config\Fixtures\SetStateA::__set_state([
        'foo' => 'bar',
        'baz' => Fi1a\Unit\Config\Fixtures\SetStateB::__set_state([
            'qux' => 'quz',
        ]),
    ]),
];
PHP;

        $parser = new PHPParser('UTF-8', true);
        $this->assertEquals($php, $parser->encode($array));
    }
}
