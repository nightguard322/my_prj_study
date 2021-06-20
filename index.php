<?php

//Основная логическая структура

define('VG_ACCESS', true);

header('Content-type:text/html;charset=utf-8');
session_start();

require_once('config.php');
require_once('core/base/settings/internal_settings.php');
require_once('librares/functions.php');

use core\base\controllers\RouteController;
use core\base\exeptions\RouteExeption;

try{
    RouteController::getInstance();
}
catch(RouteExeption $e){
    exit($e->getMessage());
    echo '123';
}
