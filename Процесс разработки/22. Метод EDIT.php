<?php
Class project_l22 //<--не обращать внимания, тут класс BaseModel
{
      
    // Перед созданием основного метода редактирования создадим вспомогательный метод
    // позволяющий смотреть информацию о полях (колонках) базы данных
    
    //создаем в baseMethods

    protected function showColumns($table){

        $query = "SHOW COLUMNS FROM $table";
        
        $res = $this->query($query);

        


    }






}