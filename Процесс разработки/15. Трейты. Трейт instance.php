<?php

// Трейты

// В папке user->controller создаем новый класс (файл) trait1.php

// Трейты - классы (типа абстракных), к которым можно обращаться из любого другого класса, без прямого наследования
// через use

// Создадим трейт instance, чтобы не дублировать везде код

// В папке base/controllers создаем трейт Singleton.php

// Описываем там методы clone и __construct, а также $_instance

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

// Если в классе, где подключен трейт, нет этих методов - они подключатся с трейта

// Определенный в shopSettings метод instance переопределит метод в трейте => метод instance мы переименуем в 
// static private function getInstance 
// И, далее, в самом классе shopSettings мы не обращаемся к $_instance, а выполняем метод self::instance;
static private function getInstance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;    
        }
        self::instance()->baseSettings = Settings::instance();

        Далее, метод instance со свойствами перенесем в BaseController;