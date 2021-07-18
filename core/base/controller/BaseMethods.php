<?php

namespace core\base\controller;

trait BaseMethods
{
    


    protected function clearStr($str){
        if(is_array($str)){
            foreach($str as $key=>$item){
                $str[$key] = trim(strip_tags($item));
            }
        }
    }
    protected function clearNum($num){
        return $num * 1;
    }

    protected function isAjax(){
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
    }

    protected function redirect($http = false, $code = false){
        if($code){
            $codes = ['301' => 'HTTP/1.1 301 Move Permanently'];
            if($codes[$code]) header($codes[$code]);
        }
        if($http) $redirect = $http;
            else $redirect = isset($_SERVER['HTTP_REFFERER']) ? $_SERVER['HTTP_REFFERER'] : PATH;


        header("Location: $redirect");

        exit;

        }

    protected function writeLog($message, $file = 'log.txt', $event ="fault"){

        $dateTime = new \DateTime();

        $str = $event . ':' . $dateTime->format('Y-m-d H:i:s') . '-' . $message . "\r\n";

        file_put_contents('logs/' . $file, $str, FILE_APPEND);
    }




}
