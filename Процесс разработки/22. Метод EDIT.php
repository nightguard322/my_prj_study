<?php
Class project_l22 //<--не обращать внимания, тут класс BaseModel
{
      
    // Перед созданием основного метода редактирования создадим вспомогательный метод
    // позволяющий смотреть информацию о полях (колонках) базы данных
    
    //создаем в baseMethods

    protected function showColumns($table){

        $query = "SHOW COLUMNS FROM $table";
        
        $res = $this->query($query);

        $columns = [];

        //Функция возвращает массив с информацией о каждом поле: [0]['field'] => 'id', [['type]] => тип поля,
        //[key] => 'PRI'

        //Далее этот массив (нумерованный) надо переделать в ассоциативный
        
        //В админке будет набор шаблонов с динамическим подключением
 
        if($res){

            foreach($res as $row){
                $columns[$row['Field']] = $row; //в ячейку $columns[значение в ячейке $row по ключу Field] записываем $row
                //далее нужно проверить, имеется ли первичный ключ и создать поле с названием 
                if($row['key'] === 'PRI') $columns['id_row'] = $row['Field']; //если значение по ключу key - PRI,
                //в массиве columns создаем ключ id_row со значением $row и его ключа field (чтобы понимать, что тут
                //лежит первичный ключ - получается структура, что у нас создается ассоциативный массив с именем из 
                //поля fields, и рядом ключ id_row, в котором значение - название поля, где первичный ключ)
             }

            return $columns;
        }

    }

    //И в basemodelmethods создаем метод 

    final public function upQuery($table, $set = []){


        $set['fields'] = is_array($set['fields']) && !empty($set['fields']) ? $set['fields'] : false; //проверяем есть ли такой массив и не пуст ли
        $set['files'] = is_array($set['files']) && !empty($set['files']) ? $set['files'] : false;

        if(!$set['fields'] && !$set['files']) return false;

        $set['except'] = is_array($set['except']) && !empty($set['except']) ? $set['except'] : false;

        //далее сюда нужно передавать where, operand, condition

        //в методе where уже организованна проверка, что если в where не пройдут проверки - вернется пустое значение

        if(!$set['all_rows']){ //all_rows - обработка (обновление) всех полей в базе данных

            if($set['where']){
                $where = $this->createWhere($set); //определяем $where по данным переданного запроса 
            }else{
                //мы не хотим обновить все данные в бд,но нужно понять, какие данные в бд
                //нужно обновлять (откуда их брать), в адмипанели мы будем держать поле с id (автоинкрементоное,
                //без изменения, хранится в <input type='hidden' name='id' value='5'>)
                
                $columns = $this->showColumns($table);

                if(!$columns) return false; //если нет полей - прерываем функцию, нет смысла, таблицы нет

                if($columns['id_row'] && $set['fields'][$columns['id_row']]){ //если мы затрагиваем первичный ключ
                    //(во входном массиве с полями есть такое же значение, как первичный ключ в таблице)
                    $where =  'WHERE' . $columns['id_row'] . '=' . $set['fields'][$columns['id_row']]; //определяем
                    //where запрос: WHERE поле с первичным ключом (название) = полю, пришедшему в set (id того, с чем будем работать)
                    
                    //и убираем первичный ключ из массива $set (это поле приращивается самостоятельно в бд)
                    unset($set['fields'][$columns['id_row']]);
                    
                }

                $update = $this->createUpdate($set['fields'],  $set['files'], $set['except']);

                $query = "UPDATE $table SET $update $where";

                return $this->query($query, 'u'); 


            }
        }

    }




}