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
