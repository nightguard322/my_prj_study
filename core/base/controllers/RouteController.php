<?php

namespace core\base\controllers;

use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController
{
    static private $_instance;

    public function __clone(){
            
    }

    static public function getInstance(){
        if(self::$_instance instanceof self){
            return self::$_instance;
        }

        return self::$_instance = new self; 
    }

    public function __construct()
    {
        $s = Settings::get('routes');
        $s1 = ShopSettings::instance();
    }


}
