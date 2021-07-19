<?php

namespace core\user\controller;

use core\base\controller\BaseController;
use core\admin\models\Model;

class IndexController extends BaseController
{
    protected function inputData(){
        $db = Model::instance();

        $query = "SELECT * FROM articles";

        $res = $db->query($query);
        
        exit('It is an user space');
    }

    protected function outputData(){
        $vars = func_get_arg(0);
        
       $this->page =  $this->render(TEMPLATE . 'templater', $vars);

    }
}
