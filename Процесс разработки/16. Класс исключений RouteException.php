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

    Далее работаем с классом RouteException

    namespace core\base\exсeptions;

    use \core\base\controller\BaseMethods;

class RouteExсeption extends \Exception
{
    use BaseMethods;
    protected $messages;

    public function __construct($message = '', $code = '0') //Создаем метод construct
    {
        parent::__construct($message, $code){ //Родительский метод construct

            $this->messages = include('messages.php'); //Добавляем массив с кодами ошибок

            $error = $this->getMessage() ? $this->getMessage() : $this->messages($this->getCode()); //формируем сообщение с ошибкой
            // (если есть сообщение об ошибке у родителя - выдаем его, иначе смотрим есть ли хотябы код и пишем его
            // в $this->messages - переопределяем массив с кодами ошибок)
            $error .= "\r\n" . 'file' . $this->getFile() . "\r\n" . "at line" . $this->getLine() . "\r\n"; // добавляем 
            //к ошибке переносы строк, название файла, местоположение ошибки - на какой линии
            if($this->messages[$this->getCode()] $this->message = $this->messages[$this->getCode()]; //заменяем сообщение
            //пришедшее от родителя, если у нас в массиве есть замена с таким кодом

            $this->writeLog($error); //пишем сообщение в лог
        }
    }
    
}
