<?php

declare(strict_types=1);

namespace Fi1a\Config\Parsers;

use Fi1a\Collection\Queue;
use Fi1a\Tokenizer\ITokenizer;
use Fi1a\Tokenizer\PHP\Token;
use Fi1a\Tokenizer\PHP\Tokenizer70;

use const PHP_EOL;

/**
 * Парсера PHP
 */
class PHPParser implements ParserInterface
{
    /**
     * @var bool
     */
    private $useShortArraySyntax;

    /**
     * @var string
     */
    private $indent;

    /**
     * @var string
     */
    private $encoding;

    /**
     * Конструктор
     */
    public function __construct(
        string $encoding = 'UTF-8',
        bool $useShortArraySyntax = true,
        string $indent = '4spaces'
    ) {
        $this->useShortArraySyntax = $useShortArraySyntax;
        switch ($indent) {
            case '4spaces':
                $this->indent = '    ';

                break;
            case '1tab':
                $this->indent = ' ';

                break;
            default:
                $this->indent = $indent;

                break;
        }
        $this->encoding = $encoding;
    }

    /**
     * @inheritDoc
     * @psalm-suppress PossiblyInvalidMethodCall
     */
    public function decode(string $string): array
    {
        $tokenizer = new Tokenizer70($string, $this->encoding);

        $php = '';

        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            if ($token->getType() === Token::T_OPEN_TAG) {
                continue;
            }

            $image = $token->getImage();
            $php .= $image;
        }

        return (array) eval($php);
    }

    /**
     * @inheritDoc
     * @psalm-suppress PossiblyInvalidMethodCall
     */
    public function encode(array $values): string
    {
        $php = '<?php' . PHP_EOL . PHP_EOL . 'return ' . var_export($values, true) . ';';
        $pretty = '';

        $tokenizer = new Tokenizer70($php, $this->encoding);
        $depth = 0;
        $parentheses = new Queue();

        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            $image = $token->getImage();

            if ($token->getType() === Token::T_OPEN_TAG) {
                $pretty .= $image;

                continue;
            }

            if ($token->getType() === Token::T_ARRAY && $this->useShortArraySyntax) {
                $image = '[';
                $tokenizer->next(2);
                $depth++;
                if ($tokenizer->lookAtNextType() === Token::T_CONSTANT_ENCAPSED_STRING) {
                    $image .= PHP_EOL . str_repeat($this->indent, $depth);
                }
                $parentheses->addEnd(true);
            } elseif ($token->getType() === Token::T_ARRAY && !$this->useShortArraySyntax) {
                $tokenizer->next(2);
                $image .= '(';
                $depth++;
                if ($tokenizer->lookAtNextType() === Token::T_CONSTANT_ENCAPSED_STRING) {
                    $image .= PHP_EOL . str_repeat($this->indent, $depth);
                }
                $parentheses->addEnd(true);
            } elseif ($token->getType() === Token::T_PARENTHESES_OPEN) {
                $parentheses->addEnd(false);
            } elseif ($token->getType() === Token::T_PARENTHESES_CLOSE && $this->useShortArraySyntax) {
                if ($parentheses->peekEnd()) {
                    $image = ']';
                }
                $parentheses->removeEnd();
                if ($tokenizer->lookAtNextType() !== Token::T_PARENTHESES_CLOSE) {
                    $depth--;
                }
            } elseif ($token->getType() === Token::T_PARENTHESES_CLOSE && !$this->useShortArraySyntax) {
                if ($tokenizer->lookAtNextType() !== Token::T_PARENTHESES_CLOSE) {
                    $depth--;
                }
                $parentheses->removeEnd();
            }

            if ($token->getType() === Token::T_DOUBLE_ARROW && $tokenizer->lookAtNextType() === Token::T_WHITESPACE) {
                $image = '=> ';
                $tokenizer->next();
            } elseif ($token->getType() === Token::T_WHITESPACE) {
                if ($tokenizer->lookAtNextType() === Token::T_ARRAY) {
                    $image = ' ';
                } elseif (strlen($image) > 1 || $tokenizer->lookAtNextType() === Token::T_PARENTHESES_CLOSE) {
                    $image = PHP_EOL . str_repeat(
                        $this->indent,
                        $depth - ($tokenizer->lookAtNextType() === Token::T_PARENTHESES_CLOSE ? 1 : 0)
                    );
                }
            }

            $pretty .= $image;
        }

        return $pretty;
    }
}
