<?php

namespace core\admin\controller;

use core\base\controller\BaseController;
use core\admin\models\Model;
use core\base\settings\Settings;
use core\base\exсeptions\RouteExсeption;

abstract class BaseAdmin extends BaseController
{

    protected $model;

    protected $table;
    protected $columns;

    protected $menu;
    protected $title;

    protected function inputData()
    {

        $this->init(true);
        $this->title = 'title';

        if(!$this->model) $this->model = Model::instance();
        if(!$this->menu) $this->menu = Settings::get('projectTables');

        $this->sendNoCacheHeaders();
    }

    protected function sendNoCacheHeaders()
    {
        header('Last-Modified' . gmdate("D, d m Y H:i:s") . "GMT");
        header('Cache-Control: no-cache, must-revalidate' );
        header('Cache-Control: max-age=0' );
        header('Cache-Control: pre-check=0, post-check=0' );
    }

    protected function execBase()
    {
        Self::inputData();
    
    }

    protected function createTableData()
    {
        if(!$this->table){
            if($this->parameters) $this->table = array_keys($this->parameters)[0];
                else $this->table = Settings::get('defaultTable');
        }

        $this->columns = $this->model->showColumns();

        if(!$this->columns) throw new RouteExсeption('Не найдены поля в таблице ' . $this->table, 2);


    }

}
