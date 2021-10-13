<?php
    Class project_l18 //<--не обращать внимания, тут класс BaseModel
    {
       final public function select($table, $set = []){// set - массив данных)
            /**
           * 'fields' => ['id', 'name'], --затрагиваемые поля
           * 'where' => ['id' => 1, 'name' => 'Masha'], --where
           * 'operand' => ['<>','='], --Что делать с полями в where
           * 'condition' => ['AND'] --WHERE id = 1 AND (or) name = 'Vasja'
           * 'order' => ['fio', 'name'], -- поле сортировки
           * 'order_direction' => ['ASC', 'DESK'], -- направление (прямое обратное)
           * 'limit' => '1'
             */
            //получаем поля:
            $fields = $this->createFields($table, $set); 
           // --сформирует select id (,name)
            $where = $this->createWhere($table, $set);
          //  --сформирует where
            $joinArr = $this->createJoin($table, $set);
          //  --массив, в который придет запрос, сформированный принципами JOIN
            $fields .= $joinArr['fields'];
           // --добавим то, что придет в joinArr
            $join = $joinArr['join'];
            $where .= $joinArr['where'];
           // --добавим то, что придет в joinArr

           // --Далее, то, что придет с первым методом $this->createFields будет с 
           // --лишней запятой в конце, чтобы можно было добавить данные из joinArr
          //  --а т.к. для joinArr будут использоваться те же методы, что и для fields
           // --нам нужно обрезать эту запятую

            $fields = rtrim($fields, ',');

            $order = $this->createOrder($table, $set);

            $limit = $set['limit'] ? $set['limit'] : '';

            $query = "SELECT $fields FROM $table $join $where $order $limit";
 
            return $this->query($query);

            //С переменных формируется запрос, отправляется в бд, переменные подставятся, запрос выполнится

          //  Создаем метод createFields

           // 1. Проверка есть ли ячейка fields
       }
protected function createFields($table = false, $set){

    $set['fields'] = (if_array($set['fields']) && !empty($set['fields'])) ? $set['fields'] : ['*']; //проверяем, 
    //есть ли массив с полями, не пустой ли он, иначе выбираем все (*)
    
    $table =  $table ?  $table . '.': ''; //смотрим, пришло ли название таблицы

    $fields = ''; //определяем переменную с полями

    foreach($set['fields'] as $field){ //разбираем массив с полями, поданный в функции
        $fields .= $table . $field . ','; //заполняем переменную с полями в форме название таблицы.название поля(с запятой в конце, которая 
        //потом обрежется)
    }
    return $fields;
}
    protected function createOrder($table = false, $set){

    //определяем $table и $order

    $table =  $table ?  $table . '.': '';// смотрим, пришло ли название таблицы

    $order_by = ''; //определяем переменную, которая будет результатом
    
    if(is_array($set['order']) && !empty($set['order'])){ // проверка задана ли вообще сортировка
    
        $set['order_direction'] = (if_array($set['order_direction']) && !empty($set['order_direction'])) ?
        $set['order_direction'] : ['ASK']; //проверка задан ли порядок сортировки или ASK

        $order_by = 'ORDER BY'; //Начало значения переменной - результата, которое будет дополнено
        
        $direct_count = 0; //счетчик кол-ва элементов массива порядка сортировки (может быть, чтобы разные столбцы 
        //имеют разный порядок - первый ASK, второй DESK)
  
        foreach($set['order'] as $order){ //разбиваем пришедший массив с порядком
            if($set['order_direction'][$direct_count]){ //если в массиве есть элемент порядка сортировки по ключу 0
            //(значение для первого столбца таблицы)
                $order_direction = $set['order_direction'][$direct_count]; //определяем переменную порядка сортировки
                $direct_count++;// счетчик увеличиваем
            }else{
                $order_direction = $set['order_direction'][$direct_count - 1];// берем предыдущее значение порядка сортировки

            $order_by .= $table . ' ' . $order_direction . ','; //дополняем переменную результата, добавив элемент, который сортировать,
           // порядок сортировки и запятая для следующего значения, если оно есть
            }

            return rtrim($order_by, ',');// убираем запятую
        }
    }
}
} 

// Небольшое изменение:
//     Если будет объединение запросов с помощью UNION, типа
//         (SELECT t.1 name, t.2 fio FROM t1 JOIN LEFT t2 ON t1.parent_id = t2.id WHERE t1.parent.id = 1)
//         UNION
//         (SELECT t.1 name, t.2 fio FROM t1 JOIN LEFT t2 ON t1.parent_id = t2.id WHERE t1.parent.id = 2),
//         то сортировка ORDER BY t1.name ASC не сработает, надо писать сортировку по порядковому номеру поля:
//         ORDER BY 1 ASC

