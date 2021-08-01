<?php

        final public function select($table, $set = [] - массив данных){
            /**
            'fields' => ['id', 'name'], --затрагиваемые поля
            'where' => ['id' => 1, 'name' => 'Masha'], --where
            'operand' => ['<>','='], --Что делать с полями в where
            'condition' => ['AND'] --WHERE id = 1 AND (or) name = 'Vasja'
            'order' => ['fio', 'name'], -- поле сортировки
            'order_direction' => ['ASC', 'DESK'], -- направление (прямое обратное)
            'limit' => '1'
             */
            получаем поля:
            $fields = $this->createFields($table, $set); 
            --сформирует select id (,name)
            $where = $this->createWhere($table, $set);
            --сформирует where
            $joinArr = $this->createJoin($table, $set);
            --массив, в который придет запрос, сформированный принципами JOIN
            $fields .= $joinArr['fields'];
            --добавим то, что придет в joinArr
            $join = $joinArr['join'];
            $where .= $joinArr['where'];
            --добавим то, что придет в joinArr

            --Далее, то, что придет с первым методом $this->createFields будет с 
            --лишней запятой в конце, чтобы можно было добавить данные из joinArr
            --а т.к. для joinArr будут использоваться те же методы, что и для fields
            --нам нужно обрезать эту запятую

            $fields = rtrim($fields, ',');

            $order = $this->createOrder($table, $set);

            $limit = $set['limit'] ? $set['limit'] : '';

            $query = "SELECT $fields FROM $table $join $where $order $limit;
 
            return $this->query($query);

            //С переменных формируется запрос, отправляется в бд, переменные подставятся, запрос выполнится

            Создаем метод createFields

            1. Проверка есть ли ячейка fields

protected function createFields($table = false, $set){

    $set['fields'] = (if_array($set['fields']) && !empty($set['fields'])) ? $set['fields'] : ['*'];
    
    $table =  $table ?  $table . '.': '';

    $fields = '';

    foreach($set['fields'] as $field){
        $fields .= $table . $fields;
    }
    return $fields;