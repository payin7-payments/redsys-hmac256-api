<?php

namespace Redsys\Tpv\Utils;

class Numbers
{
    public static function getRedsysAmount($amount): int
    {
        $amount = number_format(str_replace(',', '.', $amount), 2, '.', '');
        return intval(strval($amount * 100));
    }
}
