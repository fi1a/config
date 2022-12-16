<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Fixtures;

class SetStateA
{
    public $foo = 'bar';

    public $baz;

    public function __construct()
    {
        $this->baz = new SetStateB();
    }

    /**
     * @param mixed[] $array
     */
    public static function __set_state(array $array)
    {
    }
}
