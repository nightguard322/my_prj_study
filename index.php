<?php

//Основная логическая структура

define('VG_ACCESS', true);

header('Content-type:text/html;charset=utf-8');
session_start();

require_once('config.php');
require_once('core/base/settings/internal_settings.php');
require_once('librares/functions.php');

use core\base\controller\RouteController;
use core\base\exсeptions\RouteExсeption;
use core\base\exсeptions\DbException;

try{
    RouteController::instance()->route();
    exit();
}
catch(RouteExсeption $e){
    exit($e->getMessage());
}

catch(DbException $e){
    exit($e->getMessage());
}
