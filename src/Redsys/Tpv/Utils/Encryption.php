<?php

namespace Redsys\Tpv\Utils;

class Encryption
{
    /******  3DES Function  *****
     * @param $message
     * @param $key
     * @return false|string
     */
    public static function encrypt3DES($message, $key)
    {
        // Se cifra
        $l = ceil(strlen($message) / 8) * 8;
        return substr(openssl_encrypt($message . str_repeat("\0", $l - strlen($message)),
            'des-ede3-cbc', $key, OPENSSL_RAW_DATA, "\0\0\0\0\0\0\0\0"), 0, $l);
    }

    /******  Base64 Functions  *****
     * @param $input
     * @return string
     */
    public static function base64UrlEncode($input): string
    {
        return strtr(base64_encode($input), '+/', '-_');
    }

    /**
     * @param $data
     * @return string
     */
    public static function encodeBase64($data): string
    {
        return base64_encode($data);
    }

    /**
     * @param $input
     * @return false|string
     */
    public static function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function decodeBase64($data)
    {
        return base64_decode($data);
    }

    /******  MAC Function *****
     * @param $ent
     * @param $key
     * @return string
     */
    public static function mac256($ent, $key): string
    {
        return hash_hmac('sha256', $ent, $key, true);//(PHP 5 >= 5.1.2)
    }
}
