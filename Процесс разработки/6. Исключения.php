<?php

Изменим функцию, добавив исключения:
 
function loadFolder($classname){
    $classname = str.replace('\\', '/', $classname);
    if(!@include $class_name . '.php'){ @ - ограничить вывод ошибок
    throw new RouteExeption('Не верное имя файла для подключения -' . $class_name);
Если не подключается файл - выбросить исключение с сообщением

Исключения - не всегда ошибка, скорее не совсем корректное исполнение кода
(пример - зарегистрирован ли посетитель, стоит ли cookie, если нет - 
будет исключение, которым завершим работу скрипта, логика скрипта пойдет исходя из того,
что пользователь не зарегистрирован)
}

Создаем класс обработки исключений

namespace core\base\exeptions; - по имени папки

class RouteExeption extends \Exeptions (\Exeptions - искать в глобальном пространстве имен)
{
    
}

!!!Этот класс должен наследовать базовый класс обработки исключений php

Для удобного вывода класса импортириуем пространство имен (чтобы не писать пространство имен целиком перед классом)

use core\base\exeptions\RouteExeption; (в том месте, где обращаемся к классу - 
в файле internal_settings)

Как работают исключения

try{
    (new A());
}
catch(RouteExeption $e){
    exit($e->getMessage());
}


try - выполняем какой то код
ставим условие, что при определенной ситуации выбрасывается исключение
с помощью catch ловим его и обрабатываем экземпляром указанного класса (RouteExeption),
в $e передается экземпляр класса со всеми методами (getMessage())

Независимо от того, в каком месте когда генерируется исключение (в любом файле),
оно ловится с помощью catch (ближайший catch этого класса) и выполняется код внутри него