<html>
<body>
<?php

/**
 * @author     Martin Kovachev (miracle@nimasystems.com)
 * @copyright  Payin7 S.L.
 * @license    MIT
 * @datetime   2021-01-13
 */

use Redsys\Tpv\Api\Rest;
use Redsys\Tpv\DataParams;

require_once('./vendor/autoload.php');

$config = require_once('../config.php');

// setup
$tpv = new Rest();
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