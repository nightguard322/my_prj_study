<?php

Сообщения в исключениях будут использоваться для логов

меняем сообщения, описанные ранее
RouteController
if(!$this->routes) throw new RouteExсeption('Отсутствуют маршруты в базовых настройках', 1);

также заменим  else{
    try{ 
        throw new RouteExсeption('Некорректная директория сайта');
    }
    catch(RouteExсeption $e){
        exit($e->getMessage());
    }

    на 