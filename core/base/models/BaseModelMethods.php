<?php
/* методы, создающие запросы */
namespace core\base\models;

Abstract class BaseModelMethods
{

    protected $sqlFunc = ['NOW'];

    protected function createFields($set, $table = false){



        $set['fields'] = (is_array($set['fields']) && !empty($set['fields'])) ? $set['fields'] : ['*'];
        $table = $table ? $table . '.' : '';
        $fields = '';

        foreach($set['fields'] as $field){
            $fields .= $table . $field . ',';
        }
        return $fields;
        
    }

    protected function createOrder($set, $table = false){

        $table = $table ? $table . '.' : '';
        $order_by = '';

        if(is_array($set['order']) && !empty($set['order'])){

            $set['order_direction'] = (is_array($set['order_direction']) && !empty($set['order_direction']))
            ? $set['order_direction'] : ['ASK'];
            
            $order_by = 'ORDER BY ';
            $direct_count = 0;

            foreach($set['order'] as $order){
                if($set['order_direction'][$direct_count]){
                    $order_direction = strtoupper($set['order_direction'][$direct_count]);
                    $direct_count++;
                }else{
                    $order_direction = $set['order_direction'][$direct_count - 1];
                }
                $order_by .= $table . $order . ' ' . $order_direction . ',';
            }
            $order_by = rtrim($order_by, ',');
        }

        return $order_by;
    }

    protected function createWhere($set, $table = false, $instruction = "WHERE"){

        $table = $table ? $table . '.' : '';

        $where = '';

        if(is_array($set['where']) && !empty($set['where'])){

            $set['operand'] = (is_array($set['operand']) && !empty($set['operand'])) ? $set['operand'] : ['='];
            $set['condition'] = (is_array($set['condition']) && !empty($set['condition'])) ? $set['condition'] : ['AND'];
            
            $where .= $instruction;

            $o_count = 0;
            $c_count = 0;

            foreach($set['where'] as $key => $item){
               
                $where .= ' ';
                if($set['operand'][$o_count]){

                    $operand = $set['operand'][$o_count];
                    $o_count++;
                    
                }else{
                    $operand = $set['operand'][$o_count - 1];
                }

                if($set['condition'][$c_count]){
                   
                    $condition = $set['condition'][$c_count];
                    $c_count++;
                }else{
                    $condition = $set['condition'][$c_count - 1];
                }

                if($operand === "IN" || $operand === "NOT IN"){
                    if(is_string($item) && strpos($item, 'SELECT') === 0){
                        $in_str = $item;
                    
                    }else{
                        if(is_array($item)){

                            $arr_items = $item;
                        }else{

                            $arr_items = explode(',', $item);
                        }
                        $in_str = '';

                        foreach($arr_items as $arr_item){
                            $in_str .= "'" . addslashes(trim($arr_item)) . "',";
                        }
                }
                $where .= $table . $key . ' ' . $operand . " (" . trim($in_str, ',') . ") " . $condition;

                }elseif(strpos($operand, 'LIKE') !== false){

                    $lt_arr = explode('%', $operand);

                    foreach($lt_arr as $lt_key => $lt){
                        if(!$lt){
                            if(!$lt_key){
                                $item = '%' . $item;
                            }else{
                                $item .= '%';
                            }
                        }
                    }

                $where .= $table . $key . ' LIKE ' . "'" . addslashes($item) . "' " . $condition;
                    
                }else{
                    if(strpos($item, 'SELECT') === 0){
                        $where .= $table . $key . $operand . '(' . $item . ')' . $condition;
                     }else{
                        $where .= $table . $key . $operand . "'" . addslashes($item) . "'" . $condition;
                     }
                    
                }

            }  
            if($condition) $where = substr($where, 0, strrpos($where, $condition));
               
        }
        return $where;

    }

    protected function createJoin($set, $table, $new_where = false){

        $fields = '';
        $join = '';
        $where = '';
        $tables = '';

        if($set['join']){

            $join_table = $table;

            foreach($set['join'] as $key => $item){
                if(is_int($key)){
                    if(!$item['table']) continue;
                        else $key = $item['table'];
                }
                
                if($join) $join .= ' ';
                
                if(isset($item['on']) && $item['on']){

                    $join_fields = [];

                    if(isset($item['on']['fields']) && is_array($item['on']['fields']) && count($item['on']['fields']) === 2){
                   
                        $join_fields = $item['on']['fields'];
                   
                    }elseif(count($item['on']) === 2){

                        $join_fields = $item['on'];
                    
                    }else{
                        
                        continue;

                    }            

                if(!$item['type']) $join .= 'LEFT JOIN ';
                    else $join .=  strtoupper($item['type']) . ' ' . 'JOIN ';

                $join .= $key . ' ON ';

                if($item['on']['table']) $join .= $item['on']['table'];
                    else $join .= $join_table;

                $join .= '.' . $join_fields[0] . '=' . $key . '.' . $join_fields[1];

                }

                $join_table = $key;

                if($new_where){
                    if($item['where']){
                        $new_where = false;
                    }
                    $group_condition = 'WHERE';

                }else{
                
                    $group_condition = $item['group_condition'] ? $item['group_condition'] : 'AND';
                }

                $fields .= $this->createFields($item, $key);
                $where .= $this->createWhere($item, $key, $group_condition);  
           
            }

            return compact('fields', 'join', 'where');
        }

   
    }

    protected function createInsert($fields, $files, $except){

        $insert_arr = [];

        if($fields)

            $sqlFunc = ['NOW'];

            foreach($fields as $row => $field){

                if($except && in_array($row, $except)) continue;

                $insert_arr['fields'] .= $row . ",";

                if(in_array($field, $sqlFunc)){
                    
                    $insert_arr['values'] .= $field . ",";
                
                }elseif($field === NULL){

                    $insert_arr['values'] .= "NULL";

                }else{

                    $insert_arr['values'] .= "'" . addslashes($field) . "',";
                }
                
            }

        if($files){

            foreach($files as $row => $file){

                $insert_arr['fields'] .= $row . ",";

                if(is_array($file)){
                    
                    $insert_arr['values'] .= "'" . addslashes(json_encode($file)) . "',";
               
                }else{
                   
                    $insert_arr['values'] .= "'" . addslashes($file) . "',";
                }

            }
        }

        foreach($insert_arr as $key => $value) $insert_arr[$key] = rtrim($value, ',');
        

        return $insert_arr;
    }


    protected function createUpdate($fields, $files, $except){
        
        $update = [];

        if($fields){

            foreach($fields as $row => $field){

                if($except && in_array($row, $except)) continue;

                $update = $row . '=';
                
                if(in_array($field, $this->sqlFunc)) $update .= $field . ',';
                    else $update .= "'" . addslashes($field) . "',";

                }
            }
            if($files){

                foreach($files as $row => $file){

                    $update .= $row . '=';

                    if(is_array($file)) $update .= "'" . addslashes(json_encode($file)) . "',";
                        else $update .= "'" . addslashes($file) . "',";
                }

        }

        return rtrim($update, ',');



    }
// 'join' => [
//     [ //тут можно указать как имя таблицы (join_table1)так и номер (порядок в ассоциативном массиве),
//         //т.к. может возникнуть ситуация, что придется стыковать таблицу саму к себе через третью, а 
//         //два элемента с одним названием существовать не могут
//         'table' => 'join_table1', //название таблицы
//         'fields' =>  ['id as j_id', 'name as j_name'], //тут алиасы полей новой таблицы, чтобы не 
//         //спутались с такими же полями предыдущей таблицы
//         'type' => 'left',  //тип слияния (LEFT JOIN, INNER JOIN, RIGHT JOIN)
//         'where' => ['name' => 'sasha', ], //эта where будет дополнять первую where в параметрах сверху
//         'operand' => ['='], //Что делать с полями в where
//         'condition' => ['OR'], //WHERE id = 1 AND (or) name = 'Vasja'
//         'on' => [   //признак присоединения
//             'table' => 'teachers', //по умолчанию стыковка к предыдущей таблице, но можно указать явно к какой присоединять
//             'fields' => ['id', 'parent_id']  //кол-во полей, который должно быть ровно 2 (по ним идет стыковка 2х таблиц и 
//             //это почти всегда id - инткрементные идентификаторы)
//         ]
//     ],
//     [ //к этому элементу будет присоединяться следующий (следующая таблица)
//         'table' => 'join_table1', //к какой таблице присоединять
//         'fields' =>  ['id as j_id', 'name as j_name'], //тут алиасы полей новой таблицы, чтобы не 
//         //спутались с такими же полями предыдущей таблицы
//         'type' => 'left',  //тип слияния (LEFT JOIN, INNER JOIN, RIGHT JOIN)
//         'where' => ['name' => 'sasha', ], //эта where будет дополнять первую where в параметрах сверху
//         'operand' => ['='], //Что делать с полями в where
//         'condition' => ['AND'], //WHERE id = 1 AND (or) name = 'Vasja'
//         'on' => ['id', 'parent_id']  //кол-во полей, который должно быть ровно 2 (по ним идет стыковка 2х таблиц и 
//             //можно явно не писать название таблицы и что это поля, метод и сам это определит
//         ]
//     ],
    
}
