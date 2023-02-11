<?php


namespace Hatem\Payment;


class PaymentGateway
{
    protected $api_secret_key;
    protected $api_public_key;
    protected $url;

    public function __construct($secret_key, $public_key = null)
    {
        $this->api_secret_key = $secret_key;
        $this->api_public_key = $public_key;
    }

}