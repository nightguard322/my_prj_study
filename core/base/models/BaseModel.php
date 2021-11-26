<?php

namespace core\base\models;

use core\base\controller\Singleton;
use core\base\exсeptions\DbException;
use core\base\controller\BaseMethods;

class BaseModel extends BaseModelMethods
{
    use Singleton;

    protected $db;

    protected function __construct(){

        $this->db = new \mysqli(HOST, USER, PASSWORD, DB_NAME);

        if($this->db->connect_error){ 
            throw new DbException('Не удалость подключиться к базе данных ' . $this->db->connect_errno . ' :    ' . $this->db->connect_error);
        }
        $this->db->query("SET NAMES UTF8");
        
    }

    /**     
     *      
     * $crud = r = SELECT/ c = INSERT / u = UPDATE/ d = DELETE
     * 
     */
            
    final public function query($query, $crud = 'r', $return_id = false){

        $result = $this->db->query($query);

        if($this->db->affected_rows === -1){
            throw new DbException('Ошибка в SQL запросе ' . $query . '- ' . $this->db->errno . ' ' . $this->db->error);

        }

        switch($crud){

            case "r":

                if($result->num_rows){
                    $res = [];
                    for($i = 0; $i < $result->num_rows; $i++){
                        $res[] = $result->fetch_assoc();
                        
                    }
                }
                    return $res;

                    break;

            case "w":
                    if($return_id){
                        return $this->db->insert_id;
                    }

                return true;

                break;
            
            default:
                    return true;
                    
                    break;
        }
    }

    final public function sQuery($set = [], $table){

        $fields = $this->createFields($set, $table);
        $order = $this->createOrder($set, $table);
        $where = $this->createWhere($set, $table);

        if(!$where) $new_where = true;
            else $new_where = false;

        $joinArr = $this->createJoin($set, $table);

        $fields .= $joinArr['fields'];
        $where .= $joinArr['where'];
        $join = $joinArr['join'];

        $fields = rtrim($fields, ',');

        $limit = $set['limit'] ? 'LIMIT ' . $set['limit'] : '';

        $query = "SELECT $fields FROM $table $join $where $order $limit";

        // exit($query);
        
        return $this->query($query);
    }

    final public function iQuery($set, $table){

        $set['fields'] = is_array($set['fields']) && !empty($set['fields']) ? $set['fields'] : $_POST;
        $set['files'] = is_array( $set['files']) && !empty( $set['files']) ? $set['files'] : false;

        if(!$set['fields'] && !$set['files']) return false;

        $set['except'] = $set['except'] ? $set['except'] : false;
        $set['return_id'] = is_array( $set['return_id']) && !empty( $set['return_id']) ? true : false;

        $insert_arr = $this->createInsert($set['fields'], $set['files'],  $set['except']);

        if($insert_arr){
            $query = "INSERT INTO $table ({$insert_arr['fields']}) VALUES ({$insert_arr['values']})";
            return $this->query($query, 'c',  $set['return_id']);
        }
            
        return false;

    }

    final public function uQuery($set, $table){

        $set['fields'] = is_array($set['fields']) && !empty($set['fields']) ? $set['fields'] : $_POST;
        $set['files'] = is_array( $set['files']) && !empty( $set['files']) ? $set['files'] : false;

        if(!$set['fields'] && !$set['files']) exit('field - пусто');
        
        $set['except'] = $set['except'] ? $set['except'] : false;

        if(!$set['all_rows']){

            if($set['where']){

                $where = $this->createWhere($set);
            }else{

                $columns = $this->showColumns($table);

                if(!$columns) return false;

                if($columns['id_row'] && $set['fields'][$columns['id_row']]){

                    $where = 'WHERE' . $columns['id_row'] . '=' . $set['fields'][$columns['id_row']];

                    unset($set['fields'][$columns['id_row']]);

                }

            }

            $update = $this->createUpdate($set['fields'], $set['files'], $set['except']);

            $query = "UPDATE $table SET $update $where";

            return $this->query($query, 'u');

        }




    }

    final public function dQuery($set, $table){

        $table = trim($table);

        $columns = $this->showColumns($table);
        $where = $this->createWhere($set, $table);

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

            $update = $this->createUpdate($fields, false, false);

            $query = 'UPDATE ' . $table . ' SET ' . $update . $where;

        }else{

            $join_arr = $this->createJoin($set, $table, false);

            $join = $join_arr['join'];

            $join_tables = $join_arr['table'];

            $query = 'DELETE ' . $table . $join_tables . ' FROM ' . $table . ' ' . $join . $where;
        }

        return $this->query($query, 'u');
    }


    /**
     * @param $table - таблица для вставки данных
     * @param array $set - массив параметров:
     * fields => [поле => значение]; - если не указан, то обрабатывается $_POST[поле => значение]
     * разрешена передача NOW() в качестве MySql функции обычной строкой
     * files => [поле => значение]; - можно подать массив вида [поле => [массив значений]] - 
     * (сюда передавать массив с файлами, сначала готовив файлы, передаем в этот массив  и передавать в метод ADD) 
     * except => ['исключение 1', 'исключение 2'] - исключает данные элементы массива из       добавленных в запрос
     * return_id => true | false - возвращать или нет идентификатор вставленной записи
     *@return mixed - вернется либо true/false, либо какое то значение (int)
     */
    final public function showColumns($table){

        print_arr($table);
        
        $query = "SHOW COLUMNS FROM $table";

        $res = $this->query($query);

        $columns = [];

        if($res){
             
            foreach ($res as $row){

                $columns[$row['Field']] = $row;

                if($row['Key'] === 'PRI') $columns['id_row'] = $row['Field'];

            }
        }

        return $columns;

    }
}