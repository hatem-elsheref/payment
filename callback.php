<?php

header('Content-Type: application/json');
require_once 'vendor/autoload.php';

\Hatem\Payment\Logger::log($_REQUEST);

use Hatem\Payment\MyFatoorah;
$api_key = config('myFatoorah');
$myfatoorah = new MyFatoorah($api_key);

$myfatoorah->saveTransaction($_REQUEST);
