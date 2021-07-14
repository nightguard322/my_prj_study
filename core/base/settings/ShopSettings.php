<?php

namespace core\base\settings;

use \core\base\controller\BaseMethods;
use \core\base\controller\Singleton;

class ShopSettings

{
    use Singleton;
    use BaseMethods;
     
    private $baseProperites;

    private $routes = [
        
        'plugins' =>
        [
            'path' => 'core/plugins/',
            'dir' => false,
            'routes' => 
            [
                'phones' => 'catalog/pullCat/pushCat'
            ]
        ],
        'p' => ['4','5','6']
    ];


    private $templateArr = [
        'text' => ['name', 'phone', 'address', 'price', 'short'],
        'textarea' => ['content', 'keywords', 'goods_content']
    ];

    static public function get($property)
    {
        if(isset(self::getInstance()->$property))
        return self::getInstance()->$property;
    }

    static private function getInstance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;    
        }
        self::instance()->baseSettings = Settings::instance();

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

}
