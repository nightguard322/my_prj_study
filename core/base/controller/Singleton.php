<?php

namespace core\base\controller;

trait Singleton
{
    
    public function __clone(){
            
    }

    private function __contstruct()
    {

    }
    static private $_instance;

    static public function instance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;
        }

        return self::$_instance = new self;
    }
}
