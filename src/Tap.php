<?php


namespace Hatem\Payment;

class Tap extends PaymentGateway
{
    const BASE_URL = "https://api.tap.company/v2/";
    protected $authUrl = self::BASE_URL . "authorize";

    public function authorize($body =[])
    {
        $headers = ["authorization: Bearer $this->api_secret_key"];
        return Http::post($this->authUrl)->setHeaders($headers)->setBody($body)->send();
    }

    public function charge()
    {

    }
}