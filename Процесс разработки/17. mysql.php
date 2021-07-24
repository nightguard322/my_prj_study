<?php

в input data у нас будут запросы в mysql, которые будут создаваться автоматически методами, 
котоые опишем дальше


Создаем бд в phpmyadmin "cms" и в ней таблицу articles -> int, unsigned(значения от 0 до 255, вместо -127 до 127), индекс primary и AI (прибавка)
name -> varchar, длина 255, content -> text, null, price -> float numfmt_get_locale




Создаем класс обработчик исключений от бд (пока полная копия RE)

В index добавим обработчик исключений
catch(DbExсeption $e){
    exit($e->getMessage());
}

и в DbException в методе $this->writeLog($error, подпишем 'db_log.txt');

Базовый класс модели, который будут наследовать польз и админский модели

namespace core\base\model;

use core\base\contoller\Singleton;

// Class BaseModel 
{
    use Singleton;

    protected $db; //тк мы будем наследоваться от этого класса

    // Далее переопределяем класс конструктора

    private function __construct(){
        //подключение к бд и инициализация
        //св-во $db - место, где хранится объект mysqli с настройками  свойств db
        $this->db = @new \mysqli(HOST, USER, PASSWORD, DB_NAME) //инициализация подключения при помощи 
        //библиотеки mysqli, ошибки отключили с помощью @
        // у объекта mysqli сущ 2 св-ва - connect error и code ошибки подключения
        if($this->db->connect_error){
            throw new DbException('Ошибка подключения к базе данных: ' . $this->db->connect_errno (тут код ошибки) . '' .
            $this->db->connect_error);
        }
        // Далее устанавливаем кодировку соединения
        $this->mysqli->query('SET NAMES UTF8');

    }
}

Далее проверим его в IndexController:

    protected function InputData(){
        $db = BaseModel::instance();
    }

 Далее создаем метод query, который будет являться оснновным для и других вспомогательных методов

 final (теперь его нельзя переопределять) public function query($query - переменная с запроса 
 , $метод, что делать - $crud(create,read,udpate,delete) = 'r', $return_id = false - для метода вставки - 
 insert, нам необходимо получать id вставки ){

    $result = $this->db->query($query); - объект содержащий в себе выборку из бд

    if($this->db->affected_rows === -1){ если кол-во затронутых рядов = -1 (ошибка при запросе) 
        throw new DbException('Ошибка в SQL запросе: ' . $query . ' - ' . $this->db->errno (код ошибки вне запроса sql) . ' ' . $this->db->error);
        

    }
    далее проверяем, что в переменной $crud

    switch($crud){
        
        case "r":

            if($result->num_rows){ - если что то пришло из бд, иначе num_rows = 0

                $res = [];
                for($i = 0, $i < $result->num_rows; $i++){
                    $res[] = $result->fetch_assoc(); возвращает массив каждого ряда выборки, который хранится
                    в result
                }
                return $res;
            }
            return false;

            break;
        
        case "c":

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

//  Создаем новую модель в admin/model

//  namespace core\admin\models;

// use core\base\models\BaseModel;

// class Model extends BaseModel
// {

    

// }

// Организация работы:

// получение данных в контр-ре
// формирование для модели в понятном виде
// модель отправляет в бд и получает ответы

Таблицы:

category-|-id
         |-name
         |-content

products-|-id
         |-name
         |-content
         |-parent_id

Запросы:
$query = "SELECT (имя ячейки) id, name FROM (имя таблицы) products  WHERE (значение)
 name (равно или в диапазоне) IN (или NOT IN) ('Samsung','iPhone')
 (или вложенный запрос) IN (SELECT id from category)" ;

Вложенный:
$query = "SELECT id, name FROM products WHERE parent_id = 
        (SELECT id FROM category WHERE name='Apple')";
 
 Join:
$query = "SELECT products.id, products.name FROM products
LEFT JOIN category on category.id=products.parent_id
WHERE category.name='Philips'";

Join с продуктами и категориями:


teachers-|-id
         |-name
         |-content

students-|-id
         |-name
         |-content

teachers-students-|-student       
                  |-teacher 
                

$query = "SELECT teachers.id, teachers.name students.id, students.name FROM teachers
        LEFT JOIN students_teachers on teachers.id=students_teachers.teacher
        LEFT JOIN students on students.id=students_teachers.student
        ;

    Вместо того, чтобы подавать готовый запрос, он будет формироваться методом

    $table = 'teachers';

    $res = $db->get($table, {
        
        -- Массив с настройками
        'fields' => ['id', 'name'], --затрагиваемые поля
        'where' => ['id' => 1, 'name' => 'Masha'], --where
        'operand' => ['<>','='], --Что делать с полями в where
        'order_direction' => ['ASC', 'DESC'],
        'limit' => '1'

        });

        Создаем сам метод:

        в BaseModel:

        final public function select($table, $set = [] - массив данных){
            /**
            
            
             */
