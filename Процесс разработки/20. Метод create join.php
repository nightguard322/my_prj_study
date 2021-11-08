<?php
Class project_l20 //<--не обращать внимания, тут класс BaseModel
{
      

    //При применеии join сортировка ORDER BY "поле" не работает, сортировать надо по номеру поля (1, 2), поэтому
    //внесем изменения в ф-ции createOrder в месте, где мы определяем $order_by:
    if(is_int($order)) $order_by .= $order . ' ' . $order_direction . ',';
        else $order_by .= $table . $order . ' ' . $order_direction . ',';


    //Перед появлением join - запрос where может и не существовать

    //Для этого в sQuery ведем проверку: 

    final public function sQuery($table, $set = []){

        $fields = $this->createFields($table, $set);
        $order = $this->createOrder($table, $set);
        $where = $this->createWhere($table, $set);

        if(!$where) $new_where = true;
            else $new_where = false;

        $joinArr = $this->createJoin($table, $set, $new_where);

        $fields .= $joinArr['fields'];
        $where .= $joinArr['where'];
        $join = $joinArr['join'];

        $fields = rtrim($fields, ',');

        $limit = $set['limit'] ? $set['limit'] : '';

        $query = "SELECT $fields FROM $table $join $where $order $limit";

        return $this->query($query);
    }


//Далее создаем ф-ю create join
protected function createJoin($table, $set, $new_where = false){

    $fields = ''; //переменные, которые должны вернуться
    $join = '';
    $where = '';

    if($set['join']){

        $join_table = $table; //определяем первичную таблицу, к которой будем стыковать следующую, это будет первичная таблица,
        //далее эта переменная будет переопределяться

        foreach($set['join'] as $key => $item){ //далее формируем уже новую таблицу, которую будем пристыковывать
            if(is_int($key)){ //нумерованный ли массив 
                if(!$item['table']) continue; //если это нумерованный массив и нет значения таблицы, к чему крепиться,
                //то дальше нет смысла (название мб указано или в ключе асс-ного массива или в поле 'table')
                    else $key = $item['table']; //определяем эту таблицу меняя значение ключа на название
            }

            if($join) $join .= ' '; //добавляем пробел к $join - синтаксис

            //далее определяем есть ли вообще общие поля, по которым будет объединение (в кол-ве 2 шт) и определяем их

            if($item['on']){ //если есть такое значение массива и оно не пустое

                $join_fields = []; //определяем переменную с результатом

                switch(2){ //проверяем, что что-то должно равняться 2-м

                    case count($item['on']['fileds']): //в случае если кол-во элементов в этом массиве равно 2
                        $join_fields = $item['on']['fileds']; //определяем результат
                        break;

                    case count($item['on']): //если поля заданы без ключей table => название и fields => название полей
                        $join_fields = $item['on']; //определяем
                        break;

                    default: //условия не совпали
                        continue(2); //возврат к предыдущему циклу на след итерацию (не switch а еще дальше - а foreach)
                        break;
                }

                if(!$item['type']) $join .= 'LEFT JOIN'; //определяем тип присоединения
                //по умолчанию будет LEFT (если не указано явно)
                    else $join .= trim(strtoupper($item['type'])) . ' JOIN '; //иначе берем значение, приводим к большим 
                    //буквам и добавляем слово JOIN
            
                $join .= $key . ' ON '; //формируем конечную переменную $join, добавляя в нее таблицу и далее по синтаксису
           
                if($item['on']['table']) $join .= $item['on']['table']; //определяем таблицу, к которой стыкуемся
                //если она явно написана, берется это значение
                    else $join .= $join_table; //иначе берется предыдущая таблица (сначала тут таблица, которая придет вначале,
                    //потом эта переменная будет переопределяется в конце каждого цикла формирования таблицы)
                
                $join .= '.' . $join_fields[0] .  '=' . $key . '.' . $join_fields[1]; //записываем поле, по которому стыковать,
                //далее текущая таблица и ее поле, к которому стыкать
            } 

            $join_table = $key; //переопределяем таблицу, к которой стыкуется нынешняя

            // далее определяем, пришла ли $new_where, тогда where не определена при первой работе функции (она не пришла с первого метода)
           
            if($new_where){ 
                if($item['where']){ //если есть какое то доп. условие по where в массиве с join
                    $new_where = false; // убираем переключатель
                }

                $group_condition = "WHERE"; //задаем инструкцию WHERE
            }else{
                $group_condition = $item['group_condition'] ? strtoupper($item['group_condition']) : 'AND'; //WHERE уже есть, значит тут 
                //другая инструкция
            }

            $fields .= $this->createFields($key, $item); //дополняем поля теми, что пришлив join
            $where .= $this->createWhere($key, $item, $group_condition);  //дополняем where тем, что стало после join where




        }
        

    }




    }

    $res = $db->get($table, [
   /**
         * 'fields' => ['id', 'name'], --затрагиваемые поля
         * 'where' => ['id' => 1, 'name' => 'Masha'], --where
         * 'operand' => ['<>','='], --Что делать с полями в where
         * 'condition' => ['AND'] --WHERE id = 1 AND (or) name = 'Vasja'
         * 'order' => ['fio', 'name'], -- поле сортировки
         * 'order_direction' => ['ASC', 'DESK'], -- направление (прямое обратное)
         * 'limit' => '1'
         */
        'join' => [
            [ //тут можно указать как имя таблицы (join_table1)так и номер (порядок в ассоциативном массиве),
                //т.к. может возникнуть ситуация, что придется стыковать таблицу саму к себе через третью, а 
                //два элемента с одним названием существовать не могут
                'table' => 'join_table1', //название таблицы
                'fields' =>  ['id as j_id', 'name as j_name'], //тут алиасы полей новой таблицы, чтобы не 
                //спутались с такими же полями предыдущей таблицы
                'type' => 'left',  //тип слияния (LEFT JOIN, INNER JOIN, RIGHT JOIN)
                'where' => ['name' => 'sasha', ], //эта where будет дополнять первую where в параметрах сверху
                'operand' => ['='], //Что делать с полями в where
                'condition' => ['OR'], //WHERE id = 1 AND (or) name = 'Vasja'
                'on' => [   //признак присоединения
                    'table' => 'teachers', //по умолчанию стыковка к предыдущей таблице, но можно указать явно к какой присоединять
                    'fields' => ['id', 'parent_id']  //кол-во полей, который должно быть ровно 2 (по ним идет стыковка 2х таблиц и 
                    //это почти всегда id - инткрементные идентификаторы)
                ]
            ],
            [ //к этому элементу будет присоединяться следующий (следующая таблица)
                'table' => 'join_table1', //к какой таблице присоединять
                'fields' =>  ['id as j_id', 'name as j_name'], //тут алиасы полей новой таблицы, чтобы не 
                //спутались с такими же полями предыдущей таблицы
                'type' => 'left',  //тип слияния (LEFT JOIN, INNER JOIN, RIGHT JOIN)
                'where' => ['name' => 'sasha', ], //эта where будет дополнять первую where в параметрах сверху
                'operand' => ['='], //Что делать с полями в where
                'condition' => ['AND'], //WHERE id = 1 AND (or) name = 'Vasja'
                'on' => ['id', 'parent_id']  //кол-во полей, который должно быть ровно 2 (по ним идет стыковка 2х таблиц и 
                    //можно явно не писать название таблицы и что это поля, метод и сам это определит
                ]
            ],
        ]
    ])
}