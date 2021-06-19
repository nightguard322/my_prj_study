<?php

namespace core\base\settings;

use core\base\settings\ShopSettings;

class Settings
{

    static private $_instance;

    private $routes = [
        
        'admin' => [
            'name' => 'admin',
            'path' => 'core/base/admin/controller/',
            'hrUrl' => false    
        ],

        'settings' => 
        [
            'path' => 'core/base/settings',
        ],

        'plugins' =>
        [
            'path' => 'core/plugins',
            'hrUrl' => false 
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

        $basePropeties = [];

        foreach($this as $name => $item){
            $property = $class::get($name);
            
            if(is_array($property) && is_array($item)){
                 
            }
        }
        exit();
    }

    public function arrayMergeRecursive()
    {

        $arrays = func_get_args();

        $base = array_shift($arrays);

        foreach($arrays as $array){
            
        }
    
    }

}
