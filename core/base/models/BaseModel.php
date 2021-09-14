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
        $order = $this->createOrder($table, $set);
        $where = $this->createWhere($table, $set);
        $joinArr = $this->createJoin($table, $set);

        $fields .= $joinArr['fields'];
        $where .= $joinArr['where'];
        $join = $joinArr['join'];

        $fields = rtrim($fields, ',');

        $limit = $set['limit'] ? $set['limit'] : '';

        $query = "SELECT $fields FROM $table $join $where $order $limit";
        print_arr($query);

        return $this->query($query);
    }

    protected function createFields($table = false, $set){

        $set['fields'] = (is_array($set['fields']) && !empty($set['fields'])) ? $set['fields'] : ['*'];
        $table = $table ? $table . '.' : '';
        $fields = '';

        foreach($set['fields'] as $field){
            $fields .= $table . $field . ',';
        }
        return $fields;

        
    }

    protected function createOrder($table = false, $set){

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

    protected function createWhere($table = false, $set, $instruction = "WHERE"){

        $table = $table ? $table . '.' : '';

        $where = '';

        if(is_array($set['where']) && !empty($set['where'])){

            $operand = (is_array($set['operand']) && !empty($set['operand'])) ? $set['operand'] : ['='];
            $condition = (is_array($set['condition']) && !empty($set['condition'])) ? $set['condition'] : ['AND'];
            
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
                    if(is_string($item) && strpos('SELECT', $item)){
                        $in_str = $item;
                    
                    }else{
                        if(is_array($item)){

                            $arr_items = $item;
                        }else{

                            $arr_items = explode(',', $item);
                        }
                        $in_str = '';

                        foreach($arr_items as $arr_item){
                            $in_str .= "'" . trim($arr_item) . "',";
                        }
                }
                $where .= $table . $key . ' ' . $operand . " (" . trim($in_str, ',') . ") " . $condition;

                }elseif(strpos($operand, 'LIKE') !== false){
                    echo "WHERE = " . $where;
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

                $where .= $table . $key . ' LIKE ' . "'" . $item . "' " . $condition;
                    
                }else{
                    if(strpos($item, 'SELECT') === 0){
                        $where .= $table . $key . $operand . '(' . $item . ')' . $condition;
                     }else{
                        $where .= $table . $key . $operand . "'" . $item . "'" . $condition;
                     }
                    
                }

            }  
            $where = substr($where, 0, strrpos($where, $condition));  
        }
        return $where;

    }

}

    
