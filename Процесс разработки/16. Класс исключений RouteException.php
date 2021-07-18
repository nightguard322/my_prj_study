<?php

Сообщения в исключениях будут использоваться для логов

меняем сообщения, описанные ранее
RouteController
if(!$this->routes) throw new RouteExсeption('Отсутствуют маршруты в базовых настройках', 1);

также заменим  else{
    try{ 
        throw new RouteExсeption('Некорректная директория сайта', 2);
    }
    catch(RouteExсeption $e){
        exit($e->getMessage());
    }

    Далее пишем класс RouteExсeption

    namespace core\base\exсeptions;

class RouteExсeption extends \Exception

{

    protected $messages;

    use BaseMethods;

    public function __construct($message = '', $code = ''){ -- 

        parent::__construct($message = '', $code = ''); --вызов родительского класса
    }
}
