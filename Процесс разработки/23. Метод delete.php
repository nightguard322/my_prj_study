<?php
Class project_l22 //<--не обращать внимания, тут класс BaseModel
{
      
    //Создание метода удаления данных из бд, имеет функционал работы с объединениями таблиц
    //Также, если в метод передается массив с полями fields - поля обнуляются (NULL) вместо
    //удаления

    // В createUpdate делаем изменение: в том месте, где мы выводим данные (ф-ция sql без
    //кавычек или значение в кавычках - мы должны добавить NULL, если пришел NULL - дефолт 
    //значение)

    //Далее в createJoin формируем массив с таблицами, которые учавствуют в JOIN запросах

    //$join_table = $key;

    //$tables .= ',' . trim($join_table);

    //и далее вернуть $tables через compact('fields', 'join', 'where', 'tables') 


    public function dQuery($set, $table){

        $table = trim($table);

        $where = $this->createWhere($set, $table);
        $columns = $this->showColumns($table);

        if(!$columns) return false;

        if(is_array($set['fields']) && !empty($set['fields'])){

            if($columns['id_row']){
                $key = array_search($columns['id_row'], $set['fields']);
                if($key !== false) unset($set['fields'][$key]);
            }
            
            $fields = [];

            foreach($set['fields'] as $field){

                $fields[$field] = $columns[$field]['Default'];
            }

            $update = $this->createUpdate($set['fields'], false, false);

            $query = 'UPDATE ' . $table . 'SET ' . $update . $where;

        }else{    

            $join_arr = $this->createJoin($set, $table);

            $join = $join_arr['join'];
            $join_tables = $join_arr['tables'];

            $query = 'DELETE ' $table . $join_tables . ' FROM ' . $table . ' ' . $join . $where;

        }

        return $this->query($query, 'u');
        
        }


}