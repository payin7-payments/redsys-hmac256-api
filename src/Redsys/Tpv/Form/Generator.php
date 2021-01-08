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

namespace Redsys\Tpv\Form;

use Redsys\Tpv\DataParams;
use Redsys\Tpv\RedsysApi;

/**
 * Class RedsysAPI
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
        $merchant_parameters = $this->generateMerchantParameters($signature, $signature_version, [
            'ds_merchant_parameter_prefix' => DataParams::FH_DS_MERCHANT_PARAMETER_PREFIX,
        ]);

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
