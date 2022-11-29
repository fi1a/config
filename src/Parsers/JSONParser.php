<?php

declare(strict_types=1);

namespace Fi1a\Config\Parsers;

use Fi1a\Config\Exceptions\ParserException;
use JsonException;

use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_UNICODE;

/**
 * Парсера JSON
 */
class JSONParser implements ParserInterface
{
    /**
     * @var int
     */
    private $depth;

    /**
     * @var int
     */
    private $flags;

    /**
     * Конструктор
     */
    public function __construct(?int $depth = null, ?int $flags = null)
    {
        if (is_null($depth)) {
            $depth = 512;
        }
        if (is_null($flags)) {
            $flags = JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR;
        }
        $this->depth = $depth;
        $this->flags = $flags;
    }

    /**
     * @inheritDoc
     */
    public function decode(string $string): array
    {
        try {
            /**
             * @var mixed $return
             */
            $return = json_decode($string, true, $this->depth, $this->flags);
            if (!is_array($return)) {
                $return = [];
            }

            return $return;
        } catch (JsonException $exception) {
            throw new ParserException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @inheritDoc
     */
    public function encode(array $values): string
    {
        try {
            return json_encode($values, $this->flags, $this->depth);
        } catch (JsonException $exception) {
            throw new ParserException($exception->getMessage(), $exception->getCode());
        }
    }
}
