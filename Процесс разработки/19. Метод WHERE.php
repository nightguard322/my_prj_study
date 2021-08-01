<?php
Class project_l19 //<--не обращать внимания, тут класс BaseModel
{
       protected function createWhere($table, $set = [], $instruction = "WHERE"){
        //$instruction может изменяться в JOIN если нужно будет передать, например, AND

        $table = $table ? $table . '.' : ''; //определяем есть ли название таблицы

        $where = $instruction; //выбираем режим работы из входных данных - WHERE или AND

        $o_count = 0; //счетчик, если придет несколько операндов (=, <>, IN, NOT, LIKE);
        $c_count = 0; //счетчик, если придет несколько выборов (AND, OR);

        foreach($set['where'] as $key->$value){ //разбираем массив на ключ-значение, т.к.
            //подаваться в ф-ию он будет именно так ('where' => ['id' => 1, 'name' => 'Masha'])

            $where .= ' '; //добавляем пробел (такой синтаксис sql)

            if($set['operand'][$o_count]){ //проверяем есть ли в массиве с настройками первое значение по ключу 0
                $operand = $set['operand'][$o_count];  //присваиваем его в переменную операнда
                $o_count++; //увеличиваем счетчик
            }else{
                $operand = $set['operand'][$o_count - 1]; //берем предыдущее значение (первое будет всегда)
            }
            

            if($set['condition'][$c_count]){ //аналогично см. выше
                $condition = $set['condition'][$c_count];  
                $c_count++;
            }else{
                $condition = $set['condition'][$c_count - 1];
            }

            //в операндах может быть и вложенный запрос - IN/NOT IN (SELECT... FROM ...) или LIKE (SELECT...)
            if($operand === "IN" || $operand === "NOT IN"){

                if(is_string($item) && strpos($item, 'SELECT')){
                    $in_str =  $item;

                }
            }
            

        }
       }
} 