<?php

require_once('./vendor/autoload.php');

$config = require_once('../config.php');

// setup
$tpv = new \Redsys\Tpv\Form\Generator();
$tpv->setSigningKey($config['signing_key'])
    ->setSigningKeyVer($config['signing_key_ver'])
    ->setExtraFormContent('<button type="submit">Submit</button>')
    ->setData($config['merchant_params']);

?>
<html lang="es">
<head>
</head>
<body>
<?php echo $tpv->build(); ?></body>
</html>
