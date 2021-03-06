<?php

namespace core\base\settings;

use core\base\settings\ShopSettings;

class Settings
{
    use \core\base\controller\Singleton;

    private $routes = [
        
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false,
            'dir' => '',
            'routes' =>  
            [    
                'products' => 'items/getGoods/sale',
                'phone' => 'catalog/input/output'
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
            'path' => 'core/user/controller/',
            'hrUrl' => true,
            'routes' =>  
                [    
                    'basePath' => 'index/basicInput',
                    'catalog' => 'site/inbox/outbox'
                ]
        
        ],
        'default' => 
        [
           'controller' => 'IndexController',
           'inputMethod' => 'inputData',
           'outputMethod' => 'outputData' 
        ],
        'p' => ['1','2','3']
        
        
    ];

    private $templateArr = [
        'text' => ['name', 'phone', 'address', 'secret'],
        'textarea' => ['content', 'keywords']
    ];
    
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
                        if(!in_array($value, $base)) $base[] = $value;
                        continue;
                    }     
                    $base[$key] = $value;
                }
            }
            
        }

        return $base;
    }

}
