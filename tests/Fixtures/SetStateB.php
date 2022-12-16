<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Fixtures;

class SetStateB
{
    public $qux = 'quz';

    /**
     * @param mixed[] $array
     */
    public static function __set_state(array $array)
    {
    }
}
