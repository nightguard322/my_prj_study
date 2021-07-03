<?php

namespace core\base\settings;

use core\base\settings\ShopSettings;

class Settings
{

    static private $_instance;

    private $routes = [
        
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/base/admin/controller/',
            'hrUrl' => false,
            'routes' =>  
            [    

            ]    
        ],

        'settings' => //путь к настройкам плагинов
        [
            'path' => 'core/base/settings/',
        ],

        'plugins' =>
        [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => false //или может быть /%controller/
        ],
        'user' =>
        [
            'path' => 'core/base/user/controller/',
            'hrUrl' => true,
            'routes' =>  
                [    

                ]
        
        ],
        'default' => 
        [
           'controller' => 'indexController',
           'inputMethod' => 'inputData',
           'outputMethod' => 'outputData' 
        ]    
        
        
    ];

    private $templateArr = [
        'text' => ['name', 'phone', 'address'],
        'textarea' => ['content', 'keywords']
    ];
    
    

    private function __clone()
    {

    }

    static public function instance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;
        }

        return self::$_instance = new self;
    }
    
    private function __contstruct()
    {

    }

    static public function get($propety)
    {
        return self::instance()->$propety;
    }

    public function cluePropteties($class)
    {

        $baseProperites = [];

        foreach($this as $name => $item){
            
            $property = $class::get($name);

           
            if(is_array($property) && is_array($item)){
                $baseProperites[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }
            if(!$property) $baseProperites[$name] = $this->$name;
        }
        return $baseProperites;
    }

    public function arrayMergeRecursive()
    {

        $arrays = func_get_args();

        $base = array_shift($arrays);

        foreach($arrays as $array){
            foreach($array as $key => $value){
                if(is_array($value) && is_array($base[$key])){
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                }else{
                    if(is_int($key)){
                        if(!in_array($value, $base)) array_push($base, $value);
                        
                    }       
                    $base[$key] = $value;
                    continue;
                }
            }
            
        }
        return $base;
    }

}
