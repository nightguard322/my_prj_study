<?php

namespace core\base\models;

use core\base\controller\Singleton;
use core\base\exсeptions\DbException;

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

    final public function uQuery($set, $table){

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

    final public function showColumns($table){

        $query = "SHOW COLUMNS FROM $table";

        $res = $this->query($query);

        exit();
        $columns = '';
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
}