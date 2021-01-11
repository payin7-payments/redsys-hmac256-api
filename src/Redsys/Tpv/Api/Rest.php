<?php

namespace Redsys\Tpv\Api;

use Curl\Curl;
use Redsys\Tpv\DataParams;
use Redsys\Tpv\Exceptions\SermepaResponseException;
use Redsys\Tpv\Exceptions\TpvException;
use Redsys\Tpv\RedsysApi;
use Redsys\Tpv\StatusCodes;

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

    protected $service_paths = [
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
        $path = isset($this->service_paths[$service]) ? $this->service_paths[$service] : null;

        if (!$path) {
            throw new TpvException('Service not found');
        }

        return $this->getUrl() . $path;
    }

    /**
     * @param array $request_data
     * @param Curl|null $curl
     * @return object
     * @throws SermepaResponseException
     * @throws TpvException
     */
    public function postInitiaPeticion(array &$request_data = [], Curl &$curl = null): object
    {
        return $this->postServiceData(self::SERVICE_INICIA, $request_data, $curl);
    }

    /**
     * @param array $request_data
     * @param Curl|null $curl
     * @return object
     * @throws SermepaResponseException
     * @throws TpvException
     */
    public function postTrataPeticion(array &$request_data = [], Curl &$curl = null): object
    {
        return $this->postServiceData(self::SERVICE_TRATA, $request_data, $curl);
    }

    protected function filterRequestData(array &$data)
    {
        //
    }

    /**
     * @param $service
     * @param array $request_data
     * @param Curl|null $curl
     * @return object
     * @throws SermepaResponseException
     * @throws TpvException
     */
    public function postServiceData($service, array &$request_data, Curl &$curl = null): object
    {
        $signature = '';
        $signature_version = '';
        $merchant_parameters = $this->generateMerchantParameters($signature, $signature_version, [
            'ds_merchant_parameter_prefix' => DataParams::FH_DS_MERCHANT_PARAMETER_PREFIX,
        ]);

        $request_data = [
            DataParams::FH_DS_MERCHANT_PARAMETERS => $merchant_parameters,
            DataParams::FH_DS_SIGNATURE => $signature,
            DataParams::FH_DS_SIGNATURE_VERSION => $signature_version,
        ];

        $this->filterRequestData($request_data);

        $curl = null;
        return $this->post($service, $request_data, $curl);
    }

    /**
     * @param $service
     * @param array $data
     * @param Curl|null $curl
     * @return object
     * @throws SermepaResponseException
     * @throws TpvException
     */
    protected function post($service, array $data, Curl &$curl = null): object
    {
        $curl = $this->getCurlInstance();
        $url = $this->getServiceUrl($service);
        $response = $curl->post($url, json_encode($data));

        $response_code = $curl->getHttpStatusCode();

        if ($response_code != 200 || !is_object($response)) {
            throw new TpvException('Invalid response data');
        }

        // errorous case
        if (property_exists($response, DataParams::FH_DS_ERROR_CODE)) {
            $code_orig = ((array)$response)[DataParams::FH_DS_ERROR_CODE];
            $code = stristr($code_orig, 'SIS0') ? '9' . str_replace('SIS0', '', $code_orig) : $code_orig;
            $sta = StatusCodes::getMessage($code);
            $message = $sta ? $sta['title'] : 'Unknown Sermepa Error (' . $code_orig . ')';
            throw new SermepaResponseException($message, $code);
        }

        if (!property_exists($response, DataParams::FH_DS_SIGNATURE_VERSION) ||
            !property_exists($response, DataParams::FH_DS_MERCHANT_PARAMETERS) ||
            !property_exists($response, DataParams::FH_DS_SIGNATURE)) {
            throw new TpvException('Missing required response parameters');
        }

        $this->validateNotification((array)$response);

        return $this->decodeMerchantParameters($response->Ds_MerchantParameters);
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
