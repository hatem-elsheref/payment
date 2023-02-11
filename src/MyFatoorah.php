<?php

namespace Hatem\Payment;

class MyFatoorah extends PaymentGateway
{
    const BASE_URL = "https://apitest.myfatoorah.com";

    public function sendPayment($data){
        $response =  $this->send('v2/SendPayment', $data);
        // save invoice id with auth user id in (invoices table) with status pending
        return $response;
    }

    private function checkInvoice($data){
        return $this->send('v2/GetPaymentStatus', $data);
    }

    public function saveTransaction($request){
        $data = [
            "Key"     => $request['paymentId'],
            "KeyType" => "PaymentId"
        ];
        $response = $this->checkInvoice($data);

        if ($response['IsSuccess']){
            // search in invoices table by invoice id = $response['Data']['InvoiceId'] and update status to paid and paymentId from null to $request['paymentId']
            // start shipping the order or make any thing depending on your business model
        }
    }

    private function send($url, $body = [], $headers = [], $is_post_method = true){
        $url = self::BASE_URL . '/' . $url;
        $headers = array_merge(["Authorization: Bearer $this->api_secret_key"], $headers);

        $request = null;
        if ($is_post_method)
            $request = Http::post($url);
        else
            $request = Http::get($url);

        return $request->setHeaders($headers)->setBody($body)->send();

    }
}