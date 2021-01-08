<?php

use Redsys\Tpv\DataParams;

return [
    'signing_key' => 'HMAC_SHA256_V1',
    'signing_key_ver' => 'sq7HjrUOBfKmC576ILgskD5srU870gJ7',
    'merchant_params' => [
        DataParams::AMOUNT => 1.23,
        DataParams::ORDER => time(),
        DataParams::MERCHANT_CODE => '999008881',
        DataParams::CURRENCY => DataParams::CURRENCY_CODE_EUR,
        DataParams::TRANSACTION_TYPE => DataParams::TRANSACTION_TYPE_STANDARD,
        DataParams::TERMINAL => '1',
        DataParams::MERCHANT_URL => 'http://merchant.com',
        DataParams::OK_URL => 'http://localhost/ok',
        DataParams::KO_URL => 'http://localhost/ko',
    ],
];
