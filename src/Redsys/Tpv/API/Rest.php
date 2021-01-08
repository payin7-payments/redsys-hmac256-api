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

namespace Redsys\Tpv\API;

use Curl\Curl;
use Redsys\Tpv\DataParams;
use Redsys\Tpv\Exceptions\TpvException;
use Redsys\Tpv\RedsysApi;

/**
 * Class RedsysAPI
 * @package Redsys\Tpv
 *
 */
class Rest extends RedsysApi
{
    const ENDPOINT_LIVE = 'https://sis.redsys.es/sis/rest';
    const ENDPOINT_TEST = 'https://sis-t.redsys.es:25443/sis/rest';

    const SERVICE_INICIA = 'initia';
    const SERVICE_TRATA = 'trata';

    private static $SERVICE_PATHS = [
        self::SERVICE_INICIA => '/iniciaPeticionREST',
        self::SERVICE_TRATA => '/trataPeticionREST',
    ];

    public static $REQUIRED_MERCHANT_PARAMS = [
        DataParams::AMOUNT,
        DataParams::CURRENCY,
        DataParams::MERCHANT_CODE,
        DataParams::ORDER,
        DataParams::TERMINAL,
        DataParams::TRANSACTION_TYPE,
    ];

    protected $user_agent = 'RedsysAPI';

    protected function getUrl(): string
    {
        return !$this->isLiveEnv() ? self::ENDPOINT_TEST : self::ENDPOINT_LIVE;
    }

    protected function getServiceUrl($service): string
    {
        $path = isset(self::$SERVICE_PATHS[$service]) ? self::$SERVICE_PATHS[$service] : null;

        if (!$path) {
            throw new TpvException('Service not found');
        }

        return $this->getUrl() . $path;
    }

    function post($service, array $data, Curl &$curl = null)
    {
        $curl = $this->getCurlInstance();
        $url = $this->getServiceUrl($service);

        $response = $curl->post($url, json_encode($data));

        return $response;
    }

    protected function getCurlInstance(): Curl
    {
        $curl = new Curl();

        $curl->setHeader('User-Agent', $this->user_agent);
        $curl->setHeader('Accept', 'application/json');
        $curl->setHeader('Content-Type', 'application/json');
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, $this->isLiveEnv());

        return $curl;
    }

    protected function validateMerchantParameters(array $data): bool
    {
        return $this->validateParameters(self::$REQUIRED_MERCHANT_PARAMS, array_keys($data));
    }
}
