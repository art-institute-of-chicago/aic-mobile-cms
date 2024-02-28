<?php

namespace App\Helpers;

class Util
{
    public const COLLECTION_OBJECT = 1;
    public const LOAN_OBJECT = 2;

    /**
     * Generate a unique ID based on a combination of two numbers.
     * @param  int   $x
     * @param  int   $y
     * @return int
     */
    public static function cantorPair($x, $y)
    {
        return (($x + $y) * ($x + $y + 1)) / 2 + $y;
    }
}
