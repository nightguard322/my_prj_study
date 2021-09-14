<?php

namespace core\user\controller;

use core\base\controller\BaseController;
use core\user\models\Model;

class IndexController extends BaseController
{
    protected function inputData(){
        $db = Model::instance();
        echo 'юзерский дефолт контроллер <br>';

        $res = $db->sQuery('students',
        [
            'fields' => ['id', 'name'],
            'where' => ['id' => 1, 'name' => 'Masha'],
            'operand' => ['IN','LIKE', '='],
            'condition' => ['AND'],
            'order' => ['id', 'name'],
            'order_direction' => ['ASC', 'DESK'],
            'limit' => '1'
        ]


            
        );
        
        exit('It is an user space');
    }

    // protected function outputData(){
    //     $vars = func_get_arg(0);
        
    //    $this->page =  $this->render(TEMPLATE . 'templater', $vars);

    // }
}
