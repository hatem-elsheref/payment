<?php

require_once 'vendor/autoload.php';
require_once 'src/config.php';
use Hatem\Payment\MyFatoorah;
$api_key = $configurations['myFatoorah'];
$myfatoorah = new MyFatoorah($api_key);
$response = $myfatoorah->initiatePayment(["InvoiceAmount" => 150, "CurrencyIso" => "EGP"]);
$paymentMethods = $response['Data']['PaymentMethods'];
$customerIdentifier = 1;
if (isset($_GET) && isset($_GET['payment_method_id'])){
    $selectedPaymentMethod = 2;
    $response = $myfatoorah->executePayment(["InvoiceValue" => 150, "CallBackUrl" => "https://96bd-197-42-25-155.ngrok.io/hello.php", "UserDefinedField"    => "CK-12345", "PaymentMethodId" => $selectedPaymentMethod]);
    $paymentUrl = $response['Data']['PaymentURL'];
    header('Location: ' . $paymentUrl);
}
if (isset($_GET) && isset($_GET['session'])){
$response   = $myfatoorah->executePayment(["InvoiceValue" => 150, "SessionId" => $_GET['session']]);
    header('Location: ' . $response['Data']['PaymentURL']);
}else{
    $response    = $myfatoorah->initiateSession(["CustomerIdentifier" => 1])['Data'];
    $sessionId   = $response['SessionId'];
    $countryCode = $response['CountryCode'];
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Pay Now</title>
    <style>
        .radio-button-section scroll{
            height: 200px!important;
        }
        #iframe{
            height: 300px!important;
        }
        .radio-button-section scroll{
            height: 150px!important;
        }
    </style>
</head>
<body>
<h1 class="text-center">Pay Now</h1>
<h3 class="text-center text-danger">150 EGY</h3>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div id="card-element"></div>
            <button id="pay" class="btn btn-success btn-block mt-5">Pay Now</button>
        </div>
        <div class="col-sm-6">
            <div class="list-group text-right" dir="rtl">
                <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                <b>   اختر طريقة الدفع</b>
                </a>
                <?php foreach($paymentMethods as $paymentMethod): ?>
                <a href="embeed.php?payment_method_id=<?=$paymentMethod['PaymentMethodId']?>" class="list-group-item list-group-item-action disabled <?php if($paymentMethod['PaymentMethodId'] == $selectedPaymentMethod) echo "bg-success"?>" dir="ltr">
               <b>
                   <?=$paymentMethod['PaymentMethodAr'] . '/' .$paymentMethod['PaymentMethodEn'] ?>
                   <span class="badge badge-danger"><?=$paymentMethod['PaymentMethodId']?></span>
               </b>
                </a>
                <?php endforeach;?>
            </div>
        </div>


    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://demo.myfatoorah.com/cardview/v2/session.js"></script>

<script>


    console.log('from backend session id is ' + "<?=$sessionId?>");
    document.onload = function () {

        var config = {
            countryCode: "<?=$countryCode?>",
            sessionId: "<?=$sessionId?>",
            cardViewId: "card-element",
        };
        myFatoorah.init(config);
    }()

    function submitForm(){
        myFatoorah.submit().then(
            function (response) {
                // In case of success
                // Here you need to pass session id to you backend here
                var sessionId = response.sessionId;
                var cardBrand = response.cardBrand; //cardBrand will be one of the following values: Master, Visa, Mada, Amex
                console.log('from frontend session id is ' + sessionId);
                console.log('from frontend card brand is ' + cardBrand);

                document.location.href = document.location.href + "?session=" + sessionId
            },
            function (error) {
                // In case of errors
                console.log(error);
            }
        );
    }
    document.getElementById('pay').addEventListener('click', submitForm)
</script>
</body>
</html>

