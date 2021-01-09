<?php

namespace Redsys\Tpv;

use ArrayAccess;
use Redsys\Tpv\Exceptions\TpvException;
use Redsys\Tpv\Utils\Encryption;
use Redsys\Tpv\Utils\Numbers;
use Redsys\Tpv\Utils\Signature;

/**
 * Class RedsysAPI
 * @package Redsys\Tpv
 *
 */
class RedsysApi implements ArrayAccess
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

    public function setData(array $data): RedsysApi
    {
        $this->data = $data;
        return $this;
    }

    public function getDataOrder(array $data = null): ?string
    {
        $data = $data ?: $this->data;

        return isset($data['order']) ? $data['order'] : null;
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
            $full_key = strtoupper($ds_merchant_parameter_prefix . $key);
            $filtered_data[$full_key] = $val;
            unset($key, $val);
        }

        return $filtered_data;
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

        $decoded_merchant_parameters = $this->decodeMerchantParameters($merchant_data);

        if (!$decoded_merchant_parameters || !isset($order[DataParams::ORDER])) {
            throw new TpvException('Invalid notification data', 2);
        }

        $signature_to_check = Signature::createMerchantSignature($this->signing_key, $order[DataParams::ORDER], $mp);

        if ($signature_to_check !== $signature) {
            throw new TpvException('Signature does not match', 3);
        }
    }

    protected function decodeMerchantParameters($encoded_merchant_data): array
    {
        return (array)base64_decode(json_decode($encoded_merchant_data, true));
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
        $ds_merchant_parameter_prefix = isset($options['ds_merchant_parameter_prefix']) ?
            $options['ds_merchant_parameter_prefix'] : null;

        if (!$this->signing_key) {
            throw new TpvException('Signing key not set');
        }

        $data = $this->filterMerchantData($merchant_parameters ?: $this->data);

        if ($validate && !$this->validateMerchantParameters($data)) {
            throw new TpvException('Merchant parameters cannot be validated');
        }

        $order = $this->getDataOrder($data);

        if (!$order) {
            throw new TpvException('Order not set');
        }

        $data = $this->prepareMerchantParameters($data, [
            'ds_merchant_parameter_prefix' => $ds_merchant_parameter_prefix,
        ]);

        $signature = Signature::createMerchantSignature($this->signing_key, $order, $data);
        $signature_version = $this->signature_ver;

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
