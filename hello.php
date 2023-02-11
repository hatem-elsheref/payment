<?php

header('Content-Type: application/json');
require_once 'vendor/autoload.php';

\Hatem\Payment\Logger::log($_REQUEST);


echo "success";