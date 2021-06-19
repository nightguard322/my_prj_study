<?php

namespace core\base\settings;

use core\base\settings\Settings;

class ShopSettings
{
    
    static private $_instance; 
    public $baseSettings;

    private $templateArr = [
        'text' => ['name', 'phone', 'address', 'price', 'short'],
        'textarea' => ['content', 'keywords', 'goods_content']
    ];

    static public function get($propety)
    {
        return self::instance()->$propety;
    }

    static public function instance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;    
        }
        self::$_instance = new self;
        self::$_instance->$baseSettings = Settings::instance();
        self::$_instance->$baseSettings->cluePropteties(get_class());
        return self::$_instance;
    }

    

    private function __clone()
    {

    }

    private function __contstruct()
    {

    }
}
