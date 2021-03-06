<?php

Создаем класс со свойствами плагинов (называем по имени плагина с большой буквы)

namespace core\base\settings;

class ShopSettings
{
    
    static private $_instance;

    private $templateArr = [
        'text' => ['name', 'phone', 'address', 'price', 'short'],
        'textarea' => ['content', 'keywords', 'goods_content']
    ];
}
 Но плагин - система расширений системы, поэтому расширяем основные настройки 
 Наследованием не получится, потому что основые свойства (routes) имеют private
 Если основные настройки сделать protected, то доп массивы, которые будут наследоваться
 ($templateArr) придется переписывать и дополнять 
 В итоге необходимо "склеивать" эти массивы у классов основных настроек и класса шаблона

 При создании класса расширения в паттерне singleton после первого создания класса неоьходимо
 объединить настройки (свойства) расширения с основными настройками (свойствами), для этого

 подключаем пространство имен основных настроек 

 namespace core\base\settings\Settings;

 И модернизируем функцию создания объекта класса расширешний 
 
 static public function instance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;
        }

        return self::$_instance = new self;
        При создании объекта класса настроек расширения мы должны объединить свойства с помощью
        методоа класса Settings
        Создаем переменную private $baseSettings в которую поместим объект класса Settings
        далее, обращаясь к ней, и далее к методу cluePropeties класса Settings объединяем настройки
        
        
        self::$_instance->baseSettings = Settings::$instance();
        $basePropeties = self::$_instance->baseSettings->cluePropeties(get_class());
    }

    Метод cluePropeties должен брать 2 массива (настройки - основной и расширение)
    и склеивать их
    public function cluePropteties($class)
    {

        $basePropeties = [];

        foreach($this as $name => $item){
            $property = $class::get($name);
            
            if(is_array($property) && is_array($item)){
                $baseProperites[$name] = $this->arrayMergeRecursive($this->$name, $property); склеивание двух массивов в 
                массив настроек $baseProperites по ключу $name (название свойства, при каждой итерации обновляется)
            }
        }
        exit();
    }

    Функция склеивания:

    public function arrayMergeRecursive()
    {

        $array = func_get_args(); //- т.к. мы вызываем эту функцию из другой функции
        // мы можем вытащить переменные из памяти и поместить их в $args

        // Далее нужно определить главный массив, с которым будем клеить остальные

        // Он будет первым, так что выдергиваем его ф-ей array_shift(), которая после этого
        // удаляем этот элемент из массива

        $base = array_shift($arrays);

        foreach($arrays as $array){ //разбиваем оставшийся массив
            foreach($array as $key => $value){ //бьем следующий уровень вложенности (массив многомерный)
                if(is_array($value) && is_array($base[$key])){ //если это все еще массивы и такие же есть в базовом (settings)
                    
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value); //переопределяем в базовом массиве по этому ключу
                    //значение в результат выполнения этой же функции (по сути, весь смысл - выйти из цикла, что значение уже не массив)
                }else{
                    if(is_int($key)){ //если значение не массив и ключ числовой
                        if(!in_array($value, $base)) $base[] = $value; //и если в базовом массиве нет такого значения - добавляем
                            continue; //далее код не выполняется, цикл пошел на следующую итерацию( сделано для того, чтобы
                            //обрабатывался следующий элемент массива в else, т.к. могут быть и не числовые элементы, а они будут добавляться)
                    }     
                    $base[$key] = $value; //если ключ не числовой - переопределеяем значение в базовом массиве на то, что идет с ключом
                    //в другом массиве (где дополнения)
                }
            }
            
        }

        return $base;
    }

