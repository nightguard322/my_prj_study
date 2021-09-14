<?php

namespace core\plugins\shop;

use core\base\controller\BaseController;

class CatalogController extends BaseController
{
    protected function pullCat(){
        $name = 'VVV';
        $content = $this->render('', compact('name'));
        
        return compact('header', 'content', 'footer');
    }
}
