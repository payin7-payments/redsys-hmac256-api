<?php

/**
 * NOTA SOBRE LA LICENCIA DE USO DEL SOFTWARE
 *
 * El uso de este software está sujeto a las Condiciones de uso de software que
 * se incluyen en el paquete en el documento "Aviso Legal.pdf". También puede
 * obtener una copia en la siguiente url:
 * http://www.redsys.es/wps/portal/redsys/publica/areadeserviciosweb/descargaDeDocumentacionYEjecutables
 *
 * Redsys es titular de todos los derechos de propiedad intelectual e industrial
 * del software.
 *
 * Quedan expresamente prohibidas la reproducción, la distribución y la
 * comunicación pública, incluida su modalidad de puesta a disposición con fines
 * distintos a los descritos en las Condiciones de uso.
 *
 * Redsys se reserva la posibilidad de ejercer las acciones legales que le
 * correspondan para hacer valer sus derechos frente a cualquier infracción de
 * los derechos de propiedad intelectual y/o industrial.
 *
 * Redsys Servicios de Procesamiento, S.L., CIF B85955367
 */

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
