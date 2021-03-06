<?php 
// Начало

// 1. Определение константы безопастности
// define('VG_ACCESS', true)

// Для чего - будем далее подключать файлы (include_once) например config.php и пользователь,
//  который запросит данные файла, не сможет его увидеть, если в этом же файле не определена эта
//  константа (по сути это как переключатель доступа к контенту, только у index он есть)
//  Во всех других файлах defined(VG_ACCESS) or die('access denied');

//  2. Подключаем файл конфига 
//  required_once('config.php'); - в нем хранятся базовые настройки для быстрого развертывания
//  на хостинге
 
 
//  3. Отправим пользователю заголовки с типом контента и кодировки
//  header('Content-type:text/html;charset=utf-8');

//  !!! заголовки показывать до любого контента

//  4. Далее стартовать сессию session_start (суперглобальный массив Sessions - 
 
//  это физические файлы (временные), которые создаются на стороне сервера и в них
//  хранится инфа в рамках сессии (стартует при подключении к серверу, 
//  до закрытия браузера))

//  5. Подключаем расширенный файл настроек
//  require_once('core/base/settings/internal_settings.php');
//  Настройка фундаментальных настроек 
//     - пути к шаблонам
//     - доп. настройки (безопастности)
