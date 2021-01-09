<?php

namespace Redsys\Tpv\API;

use Curl\Curl;
use Redsys\Tpv\DataParams;
use Redsys\Tpv\Exceptions\TpvException;
use Redsys\Tpv\RedsysApi;

/**
 * Class Rest
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
