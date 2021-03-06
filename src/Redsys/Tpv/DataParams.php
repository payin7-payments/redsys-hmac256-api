<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

namespace Redsys\Tpv;

class DataParams
{
    const FH_DS_MERCHANT_PARAMETER_PREFIX = 'DS_MERCHANT_';
    const FH_DS_MERCHANT_PARAMETERS = 'Ds_MerchantParameters';
    const FH_DS_SIGNATURE = 'Ds_Signature';
    const FH_DS_SIGNATURE_VERSION = 'Ds_SignatureVersion';
    const FH_DS_EMV3DS = 'Ds_EMV3DS';
    const FH_DS_ERROR_CODE = 'errorCode';

    const AMOUNT = 'amount';
    const AUTHORISATION_CODE = 'authorisationcode';
    const COF_INI = 'cof_ini';
    const COF_TXNID = 'cof_txnid';
    const COF_TYPE = 'cof_type';
    const CONSUMER_LANGUAGE = 'consumerlanguage';
    const CURRENCY = 'currency';
    const CVV2 = 'cvv2';
    const DIRECT_PAYMENT = 'directpayment';
    const EMV_3DS = 'emv3ds';
    const EXPIRY_DATE = 'expirydate';
    const GROUP = 'group';
    const IDENTIFIER = 'identifier';
    const ID_OPER = 'idoper';
    const MERCHANT_CODE = 'merchantcode';
    const MERCHANT_DATA = 'merchantdata';
    const MERCHANT_NAME = 'merchantname';
    const MERCHANT_URL = 'merchanturl';
    const ORDER = 'order';
    const PAN = 'pan';
    const PAYMETHODS = 'paymethods';
    const PRODUCT_DESCRIPTION = 'productdescription';
    const TAX_REFERENCE = 'tax_reference';
    const TITULAR = 'titular';
    const TRANSACTION_DATE = 'transactiondate';
    const TRANSACTION_TYPE = 'transactiontype';
    const OK_URL = 'urlok';
    const KO_URL = 'urlko';
    const XPAY_DATA = 'xpaydata';
    const XPAY_ORIGEN = 'xpayorigen';
    const SHIPPING_ADDRESS_PYP = 'shippingaddresspyp';
    const MERCHANT_DESCRIPTOR = 'merchantdescriptor';
    const PERSOCODE = 'persocode';
    const MPI_EXTERNAL = 'mpiexternal';
    const CUSTOMER_MOBILE = 'customer_mobile';
    const CUSTOMER_MAIL = 'customer_mail';
    const P2F_EXPIRY_DATE = 'p2f_expirydate';
    const CUSTOMER_SMS_TEXT = 'customer_sms_text';
    const P2F_XMLDATA = 'p2f_xmldata';
    const DCC = 'dcc';
    const EXCEP_SCA = 'excep_sca';
    const TERMINAL = 'terminal';
    const PAY_METHODS = 'pay_methods';

    public static function getPrefixedDataParam($key, $prefix = self::FH_DS_MERCHANT_PARAMETER_PREFIX): string
    {
        return strtoupper($prefix . $key);
    }

    public static $KNOWN_MERCHANT_PARAMS = [
        self::AMOUNT,
        self::AUTHORISATION_CODE,
        self::COF_INI,
        self::COF_TXNID,
        self::COF_TYPE,
        self::CONSUMER_LANGUAGE,
        self::CURRENCY,
        self::CVV2,
        self::DIRECT_PAYMENT,
        self::EMV_3DS,
        self::EXPIRY_DATE,
        self::GROUP,
        self::IDENTIFIER,
        self::ID_OPER,
        self::MERCHANT_CODE,
        self::MERCHANT_DATA,
        self::MERCHANT_NAME,
        self::MERCHANT_URL,
        self::ORDER,
        self::PAN,
        self::PAYMETHODS,
        self::PRODUCT_DESCRIPTION,
        self::TAX_REFERENCE,
        self::TITULAR,
        self::TRANSACTION_DATE,
        self::TRANSACTION_TYPE,
        self::OK_URL,
        self::KO_URL,
        self::XPAY_DATA,
        self::XPAY_ORIGEN,
        self::SHIPPING_ADDRESS_PYP,
        self::MERCHANT_DESCRIPTOR,
        self::PERSOCODE,
        self::MPI_EXTERNAL,
        self::CUSTOMER_MOBILE,
        self::CUSTOMER_MAIL,
        self::P2F_EXPIRY_DATE,
        self::CUSTOMER_SMS_TEXT,
        self::P2F_XMLDATA,
        self::DCC,
        self::EXCEP_SCA,
        self::TERMINAL,
        self::PAY_METHODS,
    ];

    const RESP_P_DATE = 'Ds_Date';
    const RESP_P_HOUR = 'Ds_Hour';
    const RESP_P_SECURE_PAYMENT = 'Ds_SecurePayment';
    const RESP_P_EXPIRY_DATE = 'Ds_ExpiryDate';
    const RESP_P_MERCHANT_IDENTIFIER = 'Ds_Merchant_Identifier';
    const RESP_P_CARD_COUNTRY = 'Ds_Card_Country';
    const RESP_P_CARD_TYPE = 'Ds_Card_Type';
    const RESP_P_AMOUNT = 'Ds_Amount';
    const RESP_P_CURRENCY = 'Ds_Currency';
    const RESP_P_ORDER = 'Ds_Order';
    const RESP_P_MERCHANT_CODE = 'Ds_MerchantCode';
    const RESP_P_TERMINAL = 'Ds_Terminal';
    const RESP_P_RESPONSE = 'Ds_Response';
    const RESP_P_MERCHANT_DATA = 'Ds_MerchantData';
    const RESP_P_TRANSACTION_TYPE = 'Ds_TransactionType';
    const RESP_P_CONSUMER_LANGUAGE = 'Ds_ConsumerLanguage';
    const RESP_P_AUTHORISATION_CODE = 'Ds_AuthorisationCode';
    const RESP_P_CARD_BRAND = 'Ds_Card_Brand';
    const RESP_P_MERCHANT_COF_TXN_ID = 'Ds_Merchant_Cof_Txnid';
    const RESP_P_PROCESSED_PAY_METHOD = 'Ds_ProcessedPayMethod';

    const PAY_METHOD_BIZUM = 'Z';

    const CARD_TYPE_DEBIT = 'D';
    const CARD_TYPE_CREDIT = 'C';

    const CURRENCY_CODE_EUR = 978;
    const CURRENCY_CODE_USD = 840;
    const CURRENCY_CODE_GBP = 826;
    const CURRENCY_CODE_JPY = 392;
    const CURRENCY_CODE_CHF = 756;
    const CURRENCY_CODE_CAD = 124;

    const TRANSACTION_TYPE_STANDARD = '0';
    const TRANSACTION_TYPE_PREAUTH = '1';
    const TRANSACTION_TYPE_PREAUTH_CONFIRM = '2';
    const TRANSACTION_TYPE_REFUND = '3';
    const TRANSACTION_TYPE_AUTH = '7';
    const TRANSACTION_TYPE_AUTH_CONFIRM = '8';
    const TRANSACTION_TYPE_PREAUTH_CANCEL = '9';

    const COF_INIT_REQ = 'S';
    const COF_INIT_NONREQ = 'N';

    const COF_TYPE_INSTALLMENTS = 'I';
    const COF_TYPE_RECURRING = 'R';
    const COF_TYPE_REAUTH = 'H';
    const COF_TYPE_RESUBMIT = 'E';
    const COF_TYPE_DELAYED = 'D';
    const COF_TYPE_INCREMENTAL = 'M';
    const COF_TYPE_NOSHOW = 'N';
    const COF_TYPE_OTHER = 'C';

    const EXCEP_SCA_MIT = 'MIT';
    const EXCEP_SCA_LWP = 'LWP';
    const EXCEP_SCA_MOTO = 'MOTO';
    const EXCEP_SCA_TRA = 'TRA';
}
