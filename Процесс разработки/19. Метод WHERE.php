<?php
Class project_l19 //<--не обращать внимания, тут класс BaseModel
{
       protected function createWhere($table, $set = [], $instruction = "WHERE"){
        //$instruction может изменяться в JOIN если нужно будет передать, например, AND

        $table = $table ? $table . '.' : ''; //определяем есть ли название таблицы

        $where = '';
        
        if(is_array($set['where']) && !empty($set['where'])){ //если есть массив с where и не пустой

            $set['operand'] = (is_array($set['operand']) && !empty($set['operand'])) ? $set['operand'] : ['=']; //определяем операнд (= <>, NOT,LIKE)
            $set['condition'] = (is_array($set['condition']) && !empty($set['condition'])) ? $set['condition'] : ['AND'];// определяем кондишн (AND, OR)
            
        $where = $instruction; //выбираем режим работы из входных данных - WHERE или AND
       
        $o_count = 0; //счетчик, если придет несколько операндов (=, <>, IN, NOT, LIKE);
        $c_count = 0; //счетчик, если придет несколько выборов (AND, OR);

        foreach($set['where'] as $key => $item){ //разбираем массив на ключ-значение, т.к.
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

                if(is_string($item) && strpos($item, 'SELECT')){  //проверяем позицию select-a в строке запроса
                    $in_str =  $item; //определяем переменную, которая будет на выходе (общая для всех вариантов),
                    //тут она равна переданому значению $item (будет строка)
                }else{
                    if(is_array($item)){ //если $item пришел массивом (чтобы передавать сразу несколько значений)
                        $arr_temp = $item; //определяем массив, который потом правильно преобразуем в строку (с кавычками)
                    }else{
                        $arr_temp = explode(',', $item); //если не массив - создаем массив, дробя по запятым
                    }

                    $in_str = ''; //в этом блоке else определяем конечную переменную
                    
                    foreach($arr_temp as $v){ //бьем массив и создаем правильную строку на вывод (с кавычками)
                        $in_str .= "'" . trim($v) . "',";
                    }

                }
            //определяем конечный результат при условии, что операнд(условие) = IN или NOT IN
                $where .= $table . $key . $operand . ' (' . trim($in_str) . ')' . $condition;

                exit(); //завершаем скрипт
            
            //далее проверяем операнд LIKE

            //запрос может прийти типа "SELECT * from table.name WHERE name LIKE "%Name" или "Name%" или "%Name%",
            //где будет % - там какой то текст до или после слова поиска(Name), если %Name% - ищем вхождение подстроки в строку

                //в массиве args в operand Like может прийти: [или так - '%LIKE', или так - 'LIKE%' и тд]
                //1. пока мы ищем есть ли вхождение подстроки LIKE
                 //выдаст порядковый номер вхождения подстроки в строку или false если нет вхождения
            }elseif(strpos($operand, 'LIKE') !== false){ //тождественно не равно false, ведь 0 (в случае ['LIKE']) тоже равен false,
                //но в данном случае условие не выполнится

                //2.далее проверяем есть ли знак %, для чего создаем временный массив и бьем строку по этмоу символу

                $lt_arr = explode('%', $operand);//если строка вида %LIKE - нулевой эл-т будет пустой, второй с LIKE
                //Если LIKE - в нулевом эл-те будет сразу LIKE
                //lt - like template
                foreach($lt_arr as $lt_key => $lt){
                    if(!$lt){ //подстрока LIKE отсутсвует в данной итерации в значении $lt (тут пустота - '')
                        if(!$lt_key){ //нулевой элемент массива
                            $item = '%' . $item; //тут '' - делили строку как раз по % - это и нужно подставить
                        //нулевой элемент, значит тут знак %, а значит его надо подкинуть
                        //в значение, которое мы используем с LIKE (например WHERE name LIKE %Masha)
                        }else{ 
                            $item += '%';//не первый элемент массива, во втором лежит LIKE, значит тут третий - добавляем
                            //в конце
                    } 
                $where = $table . $key . ' LIKE ' . "'" . $item  . "'" . $condition; 

                }
            }

 
        }else{ //тут ищем сразу идущий вложенный запрос (SELECT tb.name WHERE id=(SELECT...))
            if(strpos($item, 'SELECT') === 0){ //Если SELECT на первом месте - это влож запрос
                $where .= $table . $key . $operand . '(' . $item . ')' . $condition; 
                //добавляем вложенный запрос в скобках
            }else{
                $where .= $table . $key . $operand . "'" . $item . "'" . $condition;
                //самый простой запрос, не подходящий под условия выше (IN, NOT IN, LIKE, SELECT)
            }
    
    }
        $where = substr($where, 0, strrpos($where, $condition));
        //вырезаем подстроку с самой where с нулевого элемента по начало $condition
    } 
    return $where;

}
}