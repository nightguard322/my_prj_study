<?php

namespace core\admin\controller;

use core\base\controller\BaseController;
use core\admin\models\Model;

class IndexController extends BaseController
{
    protected function inputData(){
        $db = Model::instance();

        $query = "SELECT teachers.id, teachers.name, students.id as st_id, students.name as st_name
        FROM teachers
        LEFT JOIN students_teachers on teachers.id=students_teachers.teacher
        LEFT JOIN students on students.id=students_teachers.student
        ";

        $res = $db->query($query);
        
        exit('It is an admin space');
    }

    protected function outputData(){
        $vars = func_get_arg(0);
        
       $this->page =  $this->render(TEMPLATE . 'templater', $vars);

    }
}
