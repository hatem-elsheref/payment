<?php


namespace Hatem\Payment;


class Http
{

    private $url;
    private $method = 'GET';
    private $body   = [];
    private $headers= [];
    private $hasBody= false;

    public function __construct($url, $method)
    {
        $this->url = $url;
        $this->method = $method;
        $this->headers = ['content-type: application/json'];
    }

    public static function get($url)
    {
        return new Http($url, 'GET');
    }


    public static function post($url)
    {
        return new Http($url, 'POST');
    }

    public function setHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function setBody($body)
    {
        $this->hasBody = !empty($body);
        $this->body = $body;
        return $this;
    }


    public function send()
    {
        $curlHandler = curl_init();
        curl_setopt_array($curlHandler,[
            CURLOPT_URL             => $this->url,
            CURLOPT_CUSTOMREQUEST   => $this->method,
            CURLOPT_HTTPHEADER      => $this->headers,
            CURLOPT_RETURNTRANSFER  => true,
        ]);

        if ($this->hasBody)
            curl_setopt($curlHandler, CURLOPT_POSTFIELDS, json_encode($this->body));

        $response = curl_exec($curlHandler);
        $error    = curl_error($curlHandler);
        curl_close($curlHandler);

        return $this->response($error ? $error : $response);
    }

    private function response($data)
    {
        return json_decode($data, true);
    }
}