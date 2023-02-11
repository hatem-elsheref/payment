<?php

require_once 'vendor/autoload.php';
require_once 'src/config.php';

use Hatem\Payment\Tap;
$gateway = new Tap($configurations['tap']['secret']);
$amount = 40;
$currency = 'KWD';
$customer = [
    'first_name'  => 'Hatem',
    'middle_name' => 'Mohamed',
    'last_name'   => 'Elsheref',
    'email'       => 'username@domain.com',
    'phone'       => [
        'country_code'  => '+20',
        'number'        => '12345678',
    ],
];
$source = [
    'id' => 'src_all'
];
$redirect = [
    // callback after processing payment => return customer back to this url after press pay
    'url' => 'http://localhost:8080/'
];
//$response = $gateway->authorize(compact('amount', 'currency', 'customer', 'source', 'redirect'));

// redirect customer to this url to complete payment
//$url = $response['transaction']['url'];



use Hatem\Payment\MyFatoorah;
$api_key = $configurations['myFatoorah'];
$myfatoorah = new MyFatoorah($api_key);

$data = [
  "CustomerName"        => "Hatem Elsheref",
  "UserDefinedField"    => "CK-12345",
  "NotificationOption"  => "LNK",
  "DisplayCurrencyIso"  => "EGP",
  "MobileCountryCode"   => "+20",
  "CustomerMobile"      => "12345678",
  "CustomerEmail"       => "username@domain.com",
  "InvoiceValue"        => "190",
  "Language"            => "en",
  "CallBackUrl"         => "https://0a93-197-42-25-155.ngrok.io/callback.php",
//  "ErrorUrl"            => "string",
];

$url = $myfatoorah->sendPayment($data)['Data']['InvoiceURL'];

header('Location: ' . $url);