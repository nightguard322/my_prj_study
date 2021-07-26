<?php

namespace core\base\models;

use core\base\controller\Singleton;
use core\base\exсeptions\DbException;

class BaseModel
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


final public function sQuery($table, $set = []){

    $fields = $this->createFields($table, $set);
    $where = $this->createWhere($table, $set);
    $joinArr = $this->createJoin($table, $set);

    $fields .= $joinArr['fields'];
    $where .= $joinArr['where'];
    $join = $joinArr['join'];

    $fields = rtrim($fields, ',');
    $order = $this->createOrder($table, $set);
    $limit = $set['limit'] ? $set['limit'] : '';

    $query = "SELECT $fields FROM $table $join $where $order $limit";

    return $this->query($query);
}






}

    
