<?php

namespace core\base\settings;

// use core\base\settings\Settings;

class ShopSettings
{
    
    static private $_instance; 
    private $baseProperites;

    private $routes = [
        
        'admin' => [
            'name' => 'new',  
        ]
    ];


    private $templateArr = [
        'text' => ['name', 'phone', 'address', 'price', 'short'],
        'textarea' => ['content', 'keywords', 'goods_content']
    ];

    static public function get($property)
    {
        if(isset(self::instance()->$property))
        return self::instance()->$property;
    }

    static public function instance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;    
        }
        self::$_instance = new self;
        self::$_instance->baseSettings = Settings::instance();

        $baseProperites = self::$_instance->baseSettings->cluePropteties(get_class());
        self::$_instance->setProperty($baseProperites);
        return self::$_instance;
    }

    protected function setProperty($properties){
        if($properties){
            foreach ($properties as $name => $property){
                $this->$name = $property;
            }
        }
    }

    private function __clone()
    {

    }

    private function __contstruct()
    {

    }
}