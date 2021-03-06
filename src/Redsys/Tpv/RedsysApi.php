<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

namespace Redsys\Tpv;

use ArrayAccess;
use Redsys\Tpv\Exceptions\TpvException;
use Redsys\Tpv\Utils\Encryption;
use Redsys\Tpv\Utils\Numbers;
use Redsys\Tpv\Utils\Signature;

/**
 * Class RedsysApi
 * @package Redsys\Tpv
 *
 */
abstract class RedsysApi implements ArrayAccess
{
    const ENV_TEST = 'test';
    const ENV_LIVE = 'live';

    /**
     * @var string
     */
    protected $environment = self::ENV_LIVE;

    /**
     * @var string
     */
    protected $signing_key;

    /**
     * @var string
     */
    protected $signature_ver = Signature::SIGNATURE_VERSION;

    /**
     * @var string[]
     */
    protected $data = [];

    protected $supported_signature_versions = [
        Signature::SIGNATURE_VERSION,
    ];

    public function __construct(array $data = [])
    {
        $this->data = $data ?: [];
    }

    /**
     * @param string $signing_key
     * @return RedsysApi
     */
    public function setSigningKey(string $signing_key): RedsysApi
    {
        $this->signing_key = $signing_key;
        return $this;
    }

    abstract public function createMerchantParameterSignature(array $data, string &$signature_version): string;

    /**
     * @param string $signature_ver
     * @return RedsysApi
     */
    public function setSigningKeyVer(string $signature_ver): RedsysApi
    {
        $this->signature_ver = $signature_ver;
        return $this;
    }

    /**
     * @param string $env
     * @return RedsysApi
     */
    public function setEnvironment(string $env): RedsysApi
    {
        $this->environment = $env;
        return $this;
    }

    protected function isLiveEnv(): bool
    {
        return $this->environment == self::ENV_LIVE;
    }

    /**
     * @param string $key
     * @param $value
     * @return RedsysApi
     */
    public function setParam(string $key, $value): RedsysApi
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getParam(string $key): ?string
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): RedsysApi
    {
        $this->data = $data;
        return $this;
    }

    protected function validateParameters(array $keys, array $target_keys): bool
    {
        return count(array_intersect($target_keys, $keys)) == count($keys);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function validateMerchantParameters(array $data): bool
    {
        return true;
    }

    protected function prepareMerchantParameters(array $data, array $options): array
    {
        $filtered_data = [];

        $ds_merchant_parameter_prefix = isset($options['ds_merchant_parameter_prefix']) ?
            $options['ds_merchant_parameter_prefix'] : null;

        $input_data = $data;

        // update the amount to REDSYS format requirements
        if (isset($input_data[DataParams::AMOUNT])) {
            $input_data[DataParams::AMOUNT] = Numbers::getRedsysAmount($input_data[DataParams::AMOUNT]);
        }

        foreach ($input_data as $key => $val) {
            $full_key = DataParams::getPrefixedDataParam($key, $ds_merchant_parameter_prefix);
            $filtered_data[$full_key] = $val;
            unset($key, $val);
        }

        return $filtered_data;
    }

    protected function validateSignatureVersion($version)
    {
        if (!in_array($version, $this->supported_signature_versions)) {
            throw new TpvException('Unsupported signature');
        }
    }

    /**
     * @param $encoded_merchant_data
     * @param false $associative
     * @return array|object
     */
    protected function decodeMerchantParameters($encoded_merchant_data, $associative = false)
    {
        return json_decode(Encryption::base64UrlDecode($encoded_merchant_data, true), $associative);
    }

    /**
     * @param string $signature
     * @param string $signature_version
     * @param array|null $options
     * @param array|null $merchant_parameters
     * @return array|string
     * @throws TpvException
     */
    public function generateMerchantParameters(string &$signature, string &$signature_version, array $options = null,
                                               array $merchant_parameters = null)
    {
        $encode = isset($options['encode']) ? (bool)$options['encode'] : true;
        $validate = isset($options['validate']) ? (bool)$options['validate'] : true;
        $apply_filter = isset($options['filter']) ? (bool)$options['filter'] : true;
//        $ds_merchant_parameter_prefix = isset($options['ds_merchant_parameter_prefix']) ?
//            $options['ds_merchant_parameter_prefix'] : DataParams::FH_DS_MERCHANT_PARAMETER_PREFIX;

        if (!$this->signing_key) {
            throw new TpvException('Signing key not set');
        }

        $data = $merchant_parameters ?: $this->data;

        if ($apply_filter) {
            $data = $this->filterMerchantData($data);
        }

        if ($validate && !$this->validateMerchantParameters($data)) {
            throw new TpvException('Merchant parameters cannot be validated');
        }

        $data = $this->prepareMerchantParameters($data, [
            'ds_merchant_parameter_prefix' => DataParams::FH_DS_MERCHANT_PARAMETER_PREFIX,
        ]);

        $signature = $this->createMerchantParameterSignature($data, $signature_version);
        return $encode ? Encryption::encodeBase64(json_encode($data)) : $data;
    }

    protected function filterMerchantData(array $data): array
    {
        return array_filter($data, function ($val) {
            return $val !== null && strlen($val) > 0;
        });
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset): string
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}
