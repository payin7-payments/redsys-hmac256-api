<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

namespace Redsys\Tpv\Form;

use Redsys\Tpv\DataParams;
use Redsys\Tpv\Exceptions\TpvException;
use Redsys\Tpv\RedsysApi;
use Redsys\Tpv\Utils\Signature;

/**
 * Class Generator
 * @package Redsys\Tpv
 *
 */
class Generator extends RedsysApi
{
    public static $REQUIRED_MERCHANT_PARAMS = [
        DataParams::AMOUNT,
        DataParams::CURRENCY,
        DataParams::MERCHANT_CODE,
        DataParams::ORDER,
        DataParams::TERMINAL,
        DataParams::TRANSACTION_TYPE,
    ];

    const ENV_LIVE_POST_URL = 'https://sis.redsys.es/sis/realizarPago';
    const ENV_TEST_POST_URL = 'https://sis-t.redsys.es:25443/sis/realizarPago';

    /**
     * @var string
     */
    private $extra_form_content = '';

    /**
     * @var string
     */
    protected $form_name = 'redsys_payment_form';

    /**
     * @param string $form_name
     * @return Generator
     */
    public function setFormName(string $form_name): Generator
    {
        $this->form_name = $form_name;
        return $this;
    }

    /**
     * @param string $form_id
     * @return Generator
     */
    public function setFormId(string $form_id): Generator
    {
        $this->form_id = $form_id;
        return $this;
    }

    /**
     * @var string
     */
    protected $form_id = 'redsys_payment_form';

    /**
     * @return string
     */
    protected function getFormAction(): string
    {
        return $this->isLiveEnv() ? self::ENV_LIVE_POST_URL : self::ENV_TEST_POST_URL;
    }

    /**
     * @param string $extra_form_content
     * @return Generator
     */
    public function setExtraFormContent(string $extra_form_content): Generator
    {
        $this->extra_form_content = $extra_form_content;
        return $this;
    }

    protected function validateMerchantParameters(array $data): bool
    {
        return $this->validateParameters(self::$REQUIRED_MERCHANT_PARAMS, array_keys($data));
    }

    public function createMerchantParameterSignature(array $data, string &$signature_version): string
    {
        $order_key = DataParams::getPrefixedDataParam(DataParams::ORDER);
        $order = isset($data[$order_key]) ? $data[$order_key] : null;

        if (!$order) {
            throw new TpvException('Order not set');
        }

        $signature_version = $this->signature_ver;
        return Signature::createMerchantFormSignature($this->signing_key, $order, $data);
    }

    /**
     * @return array
     */
    protected function getFormAttrs(): array
    {
        return [
            'action' => $this->getFormAction(),
            'id' => $this->form_id,
            'name' => $this->form_name,
            'method' => 'post',
        ];
    }

    protected function encodeAttribute($val): string
    {
        return htmlspecialchars($val);
    }

    /**
     * @return string
     */
    protected function buildFormStart(): string
    {
        $attrs = $this->getFormAttrs();
        $attrsp = [];

        foreach ($attrs as $key => $val) {
            if ($val) {
                $attrsp[] = $key . '="' . $this->encodeAttribute($val) . '"';
            }
            unset($key, $val);
        }

        return '<form' . ($attrsp ? ' ' . implode(' ', $attrsp) : '') . '>';
    }

    /**
     * @param $name
     * @param $value
     * @return string
     */
    protected function buildHiddenInput($name, $value): string
    {
        return '<input type="hidden" name="' . $name . '" value="' . $this->encodeAttribute($value) . '"/>';
    }

    /**
     * @return string
     */
    protected function buildFormBody(): string
    {
        $signature = '';
        $signature_version = '';
        $merchant_parameters = $this->generateMerchantParameters($signature, $signature_version);

        $body = implode('', [
            $this->buildHiddenInput(DataParams::FH_DS_MERCHANT_PARAMETERS, $merchant_parameters),
            $this->buildHiddenInput(DataParams::FH_DS_SIGNATURE, $signature),
            $this->buildHiddenInput(DataParams::FH_DS_SIGNATURE_VERSION, $signature_version),
        ]);
        return $body . $this->extra_form_content;
    }

    /**
     * @return string
     */
    protected function buildFormEnd(): string
    {
        return '</form>';
    }

    /**
     * @return string
     */
    public function build(): string
    {
        return
            $this->buildFormStart() .
            $this->buildFormBody() .
            $this->buildFormEnd();
    }
}
