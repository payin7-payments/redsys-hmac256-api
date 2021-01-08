<html>
<body>
<?php

use Redsys\Tpv\DataParams;
use Redsys\Tpv\RedsysApi;

require_once('./vendor/autoload.php');

$config = require_once('../config.php');

// setup
$tpv = new RedsysApi();
$tpv->setSigningKey($config['signing_key'])
    ->setSigningKeyVer($config['signing_key_ver'])
    ->setData($config['merchant_params']);

$input_vars = !empty($_POST) ? $_POST : $_GET;

$version = $input_vars[DataParams::FH_DS_SIGNATURE_VERSION];
$merchant_data = $input_vars[DataParams::FH_DS_MERCHANT_PARAMETERS];
$signature = $input_vars[DataParams::FH_DS_SIGNATURE];

$tpv->validateNotification($input_vars);

echo 'Notification is valid';

?>
</body>
</html> 