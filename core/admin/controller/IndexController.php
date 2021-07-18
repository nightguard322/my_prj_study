<?php

namespace core\user\controller;

use core\base\controller\BaseController;
use core\base\models\BaseModel;

class IndexController extends BaseController
{
    protected function inputData(){
        $db = BaseModel::instance();

        exit('It is an user space');
    }

    protected function outputData(){
        $vars = func_get_arg(0);
        
       $this->page =  $this->render(TEMPLATE . 'templater', $vars);

    }
}
