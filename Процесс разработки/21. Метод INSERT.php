<?php
Class project_l21 //<--не обращать внимания, тут класс BaseModel
{
      

    //При применеии join сортировка ORDER BY "поле" не работает, сортировать надо по номеру поля (1, 2), поэтому
    //внесем изменения в ф-ции createOrder в месте, где мы определяем $order_by:
    
    // 1. Перенесем createFields, where, join и тд в отдельный абстрактный класс basemodelmethods

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

     final public function add($table, $set){


        $set['fields'] = is_array($set['fields']) && !empty($set['fields']) ? $set['fields'] : false; //проверяем есть ли такой массив и не пуст ли
        $set['files'] = is_array($set['files']) && !empty($set['files']) ? $set['files'] : false;
        $set['return_id'] = $set['return_id'] ? true : false; //если хоть что то пришло - отправим true, иначе false
        $set['except'] = is_array($set['except']) && !empty($set['except']) ? $set['except'] : false;

        //сначала нужно принять массив вставки. Собирать эти данные будет иной метод (работать с массивами выше), который вернет
        //поля, (fieldname1,2) и значения - value, убрав исключения и добавив функции sql без кавычек, а значения в кавычках
        $insert_arr = $this->createInsert($set['fields'],  $set['files'], $set['except']);

        if($insert_arr){ //если что то пришло
            //формируем переменную с запросом
        $query = "INSERT INTO $table ({$insert_arr['fields']}) VALUES ({$insert_arr['values']})";
        
            return $this->query($query, 'c', $set['return_id']); //выполняем запрос к бд с режими c (create) и возвратом id
        }

        return false;
        

        // результат должен быть такой: $query = "INSERT INTO table (fieldname1, fieldname2) 
        // VALUES ('value1_1', 'value1_2'), ('value2_1', 'value2_2') ";

     }


     //далее в basemodelmethods описываем createInsert
        
        protected function createInsert($fields,  $files, $except){

            // $fields будет возможность как подавать на вход, или брать инфу из $_POST 
            if(!$fields){ //проверяем, пришло ли что то в массиве с полями, или берем с $POST
                $fields = $_POST;
            }
            
            $insertArr = [];//конечный массив для формирования запроса
           
            if($fields){ //если в полях что то пришло
                //тут опишем массив с фукнциями mysql 
                $sqlFunc = ['NOW()']; 
                foreach($fields as $row => $value){ //разбираем массив с полями
                    //некоторые поля можно "сбросить" - $except (какие поля не добавлять)
                    if($except && in_array($row, $except)) continue; // если есть вообще исключения полей и в массиве с полями они присутсвют
                    // переходим на следующую итерацию
                    $insertArr['fields'] = $row . ','; //уже отфильтрованные от исключений поля добавляем в результирующий массив
                    
                    if(in_array($value, $sqlFunc)){ //ищем, если значение поля , которое пришло в этой итерации равно
                        //есть в массиве функций sql
                        $insertArr['values'] .= $value . ','; //то добавляем его без кавычек
                    }else{
                        $insertArr['values'] .= "'" . addslashes($value) . "',"; //иначе это значение приходит с кавычками
                    }
                }

            }

            //Далее с файлами
            if($files){

                foreach($files as $row => $file){

                    $insertArr['fields'] = $row;

                    if(is_array($file)) $insertArr['values'] .= "'" . addslashes(json_encode($file)) . "',";
                        else $insertArr['values'] .= "'" . addslashes($file) . "',";
                }
            }
        
            return $insertArr;
        }


}