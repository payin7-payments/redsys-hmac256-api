<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

namespace Redsys\Tpv\Utils;

class Numbers
{
    public static function getRedsysAmount($amount): int
    {
        $amount = number_format(str_replace(',', '.', $amount), 2, '.', '');
        return intval(strval($amount * 100));
    }
}
