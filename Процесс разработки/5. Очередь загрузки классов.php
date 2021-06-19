<?php

Для загрузки классов используется ф-ия  spl_autoload_register();

Для этого вначале создается функция, добавляющая класс:
function loadFolder($classname){
    include 'folder' . $classname . '.php';
}

а потом передается параметром к предыдущей (строковым)
spl_autoload_register('loadFolder', true (генерировать ли 
исключения, если нельзя загрузить класс), true (поставить в 
конец или в начало, false - в конец) );

!!!Пространство имен - область видимости класса в рамках 1-го
пространства (namespace)
Его можно описать словом namespace
namespace n1; (именовать как путь к директории)
Необходимо для обьявления классов (чтобы можно было создавать
классы с одинаковым именем в разных папках)

Обьявление класса:
(new \n2\A()); - класс А в папке n2(пространство имен)
нужно избавиться от обратных слэшей:ъ

function loadFolder($classname){
    $classname = str.replace('\\', '/', $classname); меняем слэш
    на прямой (\\ - экранируем слеш)
    include $classname . '.php';
}
