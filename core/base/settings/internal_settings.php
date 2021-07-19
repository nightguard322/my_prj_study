<?php

const TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/view/';
const COOKIE_VERSION = '1.0.0';
const CRYPT_KET = '';
const COOKIE_TIME = 0;
const BLOCK_TIME = 3;
const QTY = 8;
const QTY_LINKS = 3;
const ADMIN_CSS_JS = [
    'styles' => [''],
    'scripts' => ['']
];
const USER_CSS_JS = [
    'styles' => [''],
    'scripts' => ['']
];

use core\base\exсeptions\RouteExсeption;

function autoLoadMainClasses($class_name)
{
    $class_name = str_replace("\\", '/', $class_name);

    if(!@include $class_name . '.php'){
        if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $class_name . '.php')){

        new RouteExсeption('Не верное имя файла для подключения - ' . $class_name);
        }
    }

}

spl_autoload_register('autoLoadMainClasses');