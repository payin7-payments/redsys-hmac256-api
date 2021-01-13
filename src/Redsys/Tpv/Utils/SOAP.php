<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

namespace Redsys\Tpv\Utils;

class SOAP
{
    /**
     * @param $datos
     * @return false|string
     */
    public static function getOrderNotif($datos)
    {
        $posPedidoIni = strrpos($datos, "<Ds_Order>");
        $tamPedidoIni = strlen("<Ds_Order>");
        $posPedidoFin = strrpos($datos, "</Ds_Order>");
        return substr($datos, $posPedidoIni + $tamPedidoIni, $posPedidoFin - ($posPedidoIni + $tamPedidoIni));
    }

    /**
     * @param $datos
     * @return false|string
     */
    public static function getRequestNotif($datos)
    {
        $posReqIni = strrpos($datos, "<Request");
        $posReqFin = strrpos($datos, "</Request>");
        $tamReqFin = strlen("</Request>");
        return substr($datos, $posReqIni, ($posReqFin + $tamReqFin) - $posReqIni);
    }

    /**
     * @param $datos
     * @return false|string
     */
    public static function getResponseNotif($datos)
    {
        $posReqIni = strrpos($datos, "<Response");
        $posReqFin = strrpos($datos, "</Response>");
        $tamReqFin = strlen("</Response>");
        return substr($datos, $posReqIni, ($posReqFin + $tamReqFin) - $posReqIni);
    }

    /**
     * @param $key
     * @param $req_data
     * @return string
     */
    public static function createMerchantSignatureNotifRequest($key, $req_data): string
    {
        // Se decodifica la clave Base64
        $key = Encryption::decodeBase64($key);
        // Se obtienen los datos del Request
        $datos = self::getRequestNotif($req_data);
        // Se diversifica la clave con el Número de Pedido
        $key = Encryption::encrypt3DES(self::getOrderNotif($req_data), $key);
        // MAC256 del parámetro Ds_Parameters que envía Redsys
        $res = Encryption::mac256($datos, $key);
        // Se codifican los datos Base64
        return Encryption::encodeBase64($res);
    }

    /******  Notificaciones SOAP SALIDA *****
     * @param $key
     * @param $req_data
     * @param $order_identifier
     * @return string
     */
    public static function createMerchantSignatureNotifResponse($key, $req_data, $order_identifier): string
    {
        // Se decodifica la clave Base64
        $key = Encryption::decodeBase64($key);
        // Se obtienen los datos del Request
        $datos = self::getResponseNotif($req_data);
        // Se diversifica la clave con el Número de Pedido
        $key = Encryption::encrypt3DES($order_identifier, $key);
        // MAC256 del parámetro Ds_Parameters que envía Redsys
        $res = Encryption::mac256($datos, $key);
        // Se codifican los datos Base64
        return Encryption::encodeBase64($res);
    }
}
