<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

namespace Redsys\Tpv\Utils;

use Redsys\Tpv\DataParams;

class Signature
{
    const SIGNATURE_VERSION = 'HMAC_SHA256_V1';

    private static function encodeMerchantData(array $data): string
    {
        return Encryption::encodeBase64(json_encode($data));
    }

    /**
     * @param string $signing_key
     * @param string $order
     * @param array $merchant_data
     * @return string
     */
    public static function createMerchantFormSignature(string $signing_key, string $order, array $merchant_data): string
    {
        $signing_key = Encryption::decodeBase64($signing_key);
        $signing_key = Encryption::encrypt3DES($order, $signing_key);
        $res = Encryption::mac256(self::encodeMerchantData($merchant_data), $signing_key);
        return Encryption::encodeBase64($res);
    }

    /**
     * @param string $signing_key
     * @param array $merchant_data
     * @return string
     */
    public static function createMerchantSignature(string $signing_key, array $merchant_data): string
    {
        $signing_key = Encryption::decodeBase64($signing_key);
        $signing_key = Encryption::encrypt3DES($merchant_data[DataParams::RESP_P_ORDER], $signing_key);
        $res = Encryption::mac256(self::encodeMerchantData($merchant_data), $signing_key);
        return Encryption::encodeBase64($res);
    }

    public static function createMerchantSignature2(string $signing_key, array $merchant_data): string
    {
        $merchant_data = [

        ];

        $signing_key = Encryption::decodeBase64($signing_key);
        $signing_key = Encryption::encrypt3DES($merchant_data[DataParams::RESP_P_ORDER], $signing_key);
        $res = Encryption::mac256(self::encodeMerchantData($merchant_data), $signing_key);
        return Encryption::encodeBase64($res);
    }
}
