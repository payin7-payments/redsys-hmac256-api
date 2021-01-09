<?php

namespace Redsys\Tpv\Utils;

class Signature
{
    const SIGNATURE_VERSION = 'HMAC_SHA256_V1';

    private static function encodeMerchantData(array $data): string
    {
        return Encryption::encodeBase64(json_encode($data));
    }

    /**
     * @param $key
     * @param $order_identifier
     * @param array $merchant_data
     * @return string
     */
    public static function createMerchantSignature($key, $order_identifier, array $merchant_data): string
    {
        // Se decodifica la clave Base64
        $key = Encryption::decodeBase64($key);
        // Se diversifica la clave con el Número de Pedido
        $key = Encryption::encrypt3DES($order_identifier, $key);
        // MAC256 del parámetro Ds_MerchantParameters
        $res = Encryption::mac256(self::encodeMerchantData($merchant_data), $key);
        // Se codifican los datos Base64
        return Encryption::encodeBase64($res);
    }

    /**
     * @param $key
     * @param $order_identifier
     * @param $merchant_data
     * @return string
     */
    public function createMerchantSignatureNotif($key, $order_identifier, $merchant_data): string
    {
        // Se decodifica la clave Base64
        $key = Encryption::decodeBase64($key);
        // Se decodifican los datos Base64
        $decodec = Encryption::base64UrlDecode($merchant_data);
        // Se diversifica la clave con el Número de Pedido
        $key = Encryption::encrypt3DES($order_identifier, $key);
        // MAC256 del parámetro Ds_Parameters que envía Redsys
        $res = Encryption::mac256($decodec, $key);
        // Se codifican los datos Base64
        return Encryption::base64UrlEncode($res);
    }
}
