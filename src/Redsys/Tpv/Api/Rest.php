<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

namespace Redsys\Tpv\Api;

use Curl\Curl;
use Redsys\Tpv\DataParams;
use Redsys\Tpv\Exceptions\SermepaResponseException;
use Redsys\Tpv\Exceptions\TpvException;
use Redsys\Tpv\RedsysApi;
use Redsys\Tpv\StatusCodes;
use Redsys\Tpv\Utils\Signature;

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

    /**
     * @var string
     */
    protected string $service;

    public static $REQUIRED_MERCHANT_PARAMS = [
        DataParams::AMOUNT,
        DataParams::CURRENCY,
        DataParams::MERCHANT_CODE,
        DataParams::ORDER,
        DataParams::TERMINAL,
        DataParams::TRANSACTION_TYPE,
    ];

    protected $user_agent = 'RedsysAPI';

    public function createMerchantParameterSignature(array $data, string &$signature_version): string
    {
        $signature_version = $this->signature_ver;

        $order_key = DataParams::getPrefixedDataParam(DataParams::ORDER);
        $order = isset($data[$order_key]) ? $data[$order_key] : null;

        if (!$order) {
            throw new TpvException('Order not set');
        }

        if ($this->service == self::SERVICE_INICIA) {
            return Signature::createMerchantFormSignature($this->signing_key, $order, $data);
        } else if ($this->service == self::SERVICE_TRATA) {
            return Signature::createMerchantFormSignature($this->signing_key, $order, $data);
        } else {
            throw new TpvException('Unsupported service');
        }
    }

    /**
     * @return string
     */
    public function getService(): string
    {
        return $this->service;
    }

    /**
     * @param string $service
     * @return Rest
     */
    public function setService(string $service): Rest
    {
        $this->service = $service;
        return $this;
    }

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
     * @param mixed $request
     * @param mixed $response
     * @param Curl|null $curl
     * @return object
     * @throws SermepaResponseException
     * @throws TpvException
     */
    public function execute(&$request, &$response, Curl &$curl = null): ?object
    {
        return $this->postServiceData($request, $response, $curl);
    }

    protected function filterRequestData(array &$data)
    {
        //
    }

    /**
     * @param mixed $request
     * @param mixed $response
     * @param Curl|null $curl
     * @return object
     * @throws SermepaResponseException
     * @throws TpvException
     */
    public function postServiceData(&$request, &$response, Curl &$curl = null): ?object
    {
        if (!$this->service) {
            throw new TpvException('Service not set');
        }

        $signature = '';
        $signature_version = '';
        $merchant_parameters = $this->generateMerchantParameters($signature, $signature_version, [
            'filter' => false,
        ]);

        $request_data = [
            DataParams::FH_DS_MERCHANT_PARAMETERS => $merchant_parameters,
            DataParams::FH_DS_SIGNATURE => $signature,
            DataParams::FH_DS_SIGNATURE_VERSION => $signature_version,
        ];

        $this->filterRequestData($request_data);

        $curl = null;
        return $this->post($this->service, $request_data, $request, $response, $curl);
    }

    /**
     * @param $service
     * @param array $data
     * @param mixed $request
     * @param mixed $response
     * @param Curl|null $curl
     * @return object
     * @throws SermepaResponseException
     * @throws TpvException
     */
    protected function post($service, array $data, &$request, &$response, Curl &$curl = null): ?object
    {
        $request = json_encode($data);

        $curl = $this->getCurlInstance();
        $url = $this->getServiceUrl($service);
        $response = $curl->post($url, $request);

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

    public function validateNotification(array $data, array &$decoded_merchant_parameters = [])
    {
        $version = isset($data[DataParams::FH_DS_SIGNATURE_VERSION]) ? $data[DataParams::FH_DS_SIGNATURE_VERSION] : null;
        $merchant_data = isset($data[DataParams::FH_DS_MERCHANT_PARAMETERS]) ? $data[DataParams::FH_DS_MERCHANT_PARAMETERS] :
            null;
        $signature = isset($data[DataParams::FH_DS_SIGNATURE]) ? $data[DataParams::FH_DS_SIGNATURE] : null;

        if (!$version || !$merchant_data || !$signature) {
            throw new TpvException('Invalid notification data', 1);
        }

        $this->validateSignatureVersion($version);

        $signature = strtr($signature, '-_', '+/');

        $decoded_merchant_parameters = $this->decodeMerchantParameters($merchant_data, true);

//        $signature_to_check =
//            Signature::createMerchantSignature($this->signing_key, $decoded_merchant_parameters);
//
//        if ($signature_to_check !== $signature) {
//            throw new TpvException('Signature does not match', 3);
//        }
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
