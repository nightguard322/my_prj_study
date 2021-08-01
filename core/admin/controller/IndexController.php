<?php

namespace core\admin\controller;

use core\base\controller\BaseController;
use core\admin\models\Model;

class IndexController extends BaseController
{
    protected function inputData(){
        $db = Model::instance();

        $res = $db->sQuery('teachers', [
            'fields' => 
                ['id', 'name'],
            'order' => 
                ['name', 'content'],
            'order_direction' => 
                ['DESK', 'ASK']
        ]);
        
        exit('It is an admin space');
    }

    protected function outputData(){
        $vars = func_get_arg(0);
        
       $this->page =  $this->render(TEMPLATE . 'templater', $vars);

    }
}
