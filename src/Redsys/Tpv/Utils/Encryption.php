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
