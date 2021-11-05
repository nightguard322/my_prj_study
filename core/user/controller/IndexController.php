<?php

namespace core\user\controller;

use core\base\controller\BaseController;
use core\user\models\Model;

class IndexController extends BaseController
{
    protected function inputData(){
        $db = Model::instance();

        echo 'юзерский дефолт контроллер <br>';

        $table = 'students';

        // $res = $db->sQuery(
        // [
        //     'fields' => ['id', 'name'],
        //     'where' => [
        //         'name' => "O'henry",
        //         'surname' => 'SELECT id FROM teachers WHERE id=1'
        //     ],
        //     'operand' => ['=','NOT IN'],
        //     'condition' => ['AND'],
        //     'order' => ['id', 'name'],
        //     'order_direction' => ['ASC', 'DESC'],
        //     'limit' => '1',
        //     'join' => [
        //         [
        //             'table' => 'join_table1', //название таблицы
        //             'fields' =>  ['id as j_id', 'name as j_name'], //тут алиасы полей новой таблицы, чтобы не 
        //             //спутались с такими же полями предыдущей таблицы
        //             'type' => 'left',  //тип слияния (LEFT JOIN, INNER JOIN, RIGHT JOIN)
        //             'where' => ['name' => 'sasha', ], //эта where будет дополнять первую where в параметрах сверху
        //             'operand' => ['='], //Что делать с полями в where
        //             'condition' => ['OR'], //WHERE id = 1 AND (or) name = 'Vasja'
        //             'on' => [   //признак присоединения
        //                 'table' => 'teachers', //по умолчанию стыковка к предыдущей таблице, но можно указать явно к какой присоединять
        //                 'fields' => ['id', 'id']  //кол-во полей, который должно быть ровно 2 (по ним идет стыковка 2х таблиц и 
        //                 //это почти всегда id - инткрементные идентификаторы)
        //             ]
        //         ],
        //         [
        //             'table' => 'join_table2', //к какой таблице присоединять
        //             'fields' =>  ['id as j_id', 'name as j_name'], //тут алиасы полей новой таблицы, чтобы не 
        //             //спутались с такими же полями предыдущей таблицы
        //             'type' => 'left',  //тип слияния (LEFT JOIN, INNER JOIN, RIGHT JOIN)
        //             'where' => ['name' => 'sasha', ], //эта where будет дополнять первую where в параметрах сверху
        //             'operand' => ['='], //Что делать с полями в where
        //             'condition' => ['AND'], //WHERE id = 1 AND (or) name = 'Vasja'
        //             'on' => ['id', 'id']  //кол-во полей, который должно быть ровно 2 (по ним идет стыковка 2х таблиц и 
        //                 //можно явно не писать название таблицы и что это поля, метод и сам это определит
        //             ]
        //         ] 
        // ], $table);

        $res = $db->uQuery([
            'fields' => 
                ['name' => 'Default'],
            'files' => 
                ['main_photo' => 'default-main',
                'second_photo' => 'default-s1econd']
        ], $table);

        exit('id: ' . $res['id'] . ', name =' . $res['name'] );
    }

    // protected function outputData(){
    //     $vars = func_get_arg(0);
        
    //    $this->page =  $this->render(TEMPLATE . 'templater', $vars);

    // }
}
