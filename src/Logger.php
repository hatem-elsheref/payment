<?php

namespace Hatem\Payment;

class Logger{

    public static function log($data){
        $data = json_encode($data);
        $separator = PHP_EOL."###############################" . PHP_EOL;
        file_put_contents(date('Y-m-d') . '.log',  $data. $separator, FILE_APPEND);
    }
}