<?php

Для адаптации системы под разные проекты очень важны настройки
Пример - в админпанели будет окно редактирования статей, товаров - шаблон будет
редактироваться динамически в зависимости от типов полей (
    пример - название товара - подключается input type=text,
    выбор родительской категории - подключается select,
    пример - поле price не нужно статье, но нужно товару
)
Чтобы это подключалось динамически - необходимо это где то хранить

Создадим класс настроек

namespace core\base\settings;

class Settings
{

}
Создаем свойства:

private $routes = [
    'admin' => [ - настройки админ панели
        'name' => 'admin', - алиас для входа (www.site.ru/admin)
        'path' => 'core/admin/controller/' - путь к файлам адмпанели
        'hrUrl' => false - human readable url (ЧПУ, для пользовательской - true),
        хотя false можно не ставить, при отсутствии ячейки - будет false
        
],
    'settings' => [
        'path' => 'core/base/settings'  главный файл настроек

    ],
    'plugins' => [ плагины сайта
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
        'default' =>  настройки по умолчанию
        [
            'default' => 
            [
               'controller' => 'indexController', контроллер по умолчанию
               'inputMethod' => 'inputData'  - метод по умолчанию, который вызовется у контроллера,
               если по маршрутам не определим метод вызова
               'outputMethod' => 'outputData' - метод по умолчанию, который вызовет вывод данных в
               пользовательскую часть (разделяем сбор данных с бд, других сайтов и отдача данных в 
               пользовательские шаблоны)
            ]

        ]      

];

и применяем шаблон singleton к settings:
static private  $_instance;

private function __contstruct()
    {

    }

    private function __clone()
    {

    }

static public function instance()
{
    if(self::$_instance instanceof self){
        return self::$_instance
    }

    return self::$_instance = new self
}

Далее создаем метод возврата свойств (т.к. они private)

    static public function get($propety)
    {
        return self::instance()->$propety; при запросе настроек мы передаем обращение 
        к методу класса ClassName::get($a), метод возвращает нам экземпляр этого класса
        и мы обращаемся к его свойству ($a)
    }


    Добавляем пространство имен класса настроек в контроллер машрутов (routeController)

    use core\base\settings\Settings;

    Далее создаем метод __contstruct у класса RouteController

        Получаем настройки:
        Функцией выше обращаемся к свойству класса настроек

        public function __construct()
        {
            $s = Settings::get('routes');
        }

        Добавим массив со свойствами для плагинов

        private $templateArr = [
            'text' => ['name', 'phone', 'address'],
            'textarea' => ['content', 'keywords']
        ];


