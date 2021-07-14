<?php

namespace core\base\controller;

use core\base\exсeptions\RouteExсeption;
use core\base\settings\settings;

abstract class BaseController
{
    
    use \core\base\controller\BaseMethods;
    protected $controller;
    protected $inputMethod;
    public $outputMethod;
    protected $parameters;
    protected $page;
    protected $errors;
    protected $styles;
    protected $scripts;

    protected function init($admin = false){

        if(!$admin){
            if(USER_CSS_JS['styles']){
                foreach(USER_CSS_JS['styles'] as $item) $this->styles[] = PATH . TEMPLATE . trim($item, '/');
            }
            if(USER_CSS_JS['scripts']){
                foreach(USER_CSS_JS['scripts'] as $item) $this->scripts[] = PATH . TEMPLATE . trim($item, '/');
            }
        }
        else{
            if(ADMIN_CSS_JS['styles']){
                foreach(ADMIN_CSS_JS['styles'] as $item) $this->styles[] = PATH . TEMPLATE . trim($item, '/');
            }
            if(ADMIN_CSS_JS['scripts']){
                foreach(ADMIN_CSS_JS['scripts'] as $item) $this->scripts[] = PATH . TEMPLATE . trim($item, '/');
            }
        }
    }
    public function route(){

        $controller = str_replace('/', "\\", $this->controller);

        try{
            $object = new \ReflectionMethod($controller, 'request');
            $args = [
                'parameters' => $this->parameters,
                'inputMethod' =>  $this->inputMethod,
                'outputMethod' =>  $this->outputMethod
            ];

            $object->invoke(new $controller, $args);

        }
        catch (\ReflectionException $e){
            throw new RouteExсeption($e->getMessage());
        }
    }
    public function request($args){
        
        $this->parameters = $args['parameters'];
        $inputData = $args['inputMethod'];
        $outputData = $args['outputMethod'];

        $data = $this->$inputData();

        if(method_exists($this, $outputData)){
            $page = $this->$outputData($data);
            if($page){
                $this->page = $page;
            }
        }elseif($data){
             $this->page = $data;
        }

        if($this->errors){
            $this->writeLog($this->errors);
        }
        $this->getPage();
    }

    protected function getPage(){
        if(is_array($this->page)){
            foreach($this->page as $block) echo $block;
        
        }else{
            echo $this->page;
        }
     }

    protected function render($path = '', $parameters = []){

        extract($parameters);

        $class = new \ReflectionClass($this);
        $nmspace =  str_replace('\\', '/', $class->getNamespaceName() . '\\');
        $routes = Settings::get('routes');

        if($nmspace === $routes['user']['path']){
            $template = TEMPLATE;
        }
        else{
            $template = ADMIN_TEMPLATE;
        }

        if(!$path){
            $path = $template . explode('controller', strtolower($class->getShortName()))[0];
        }
        ob_start();
        if(!@include_once($path . '.php')){
            throw new RouteExсeption('Не верный путь к шаблону - ' . $path);
        }

        return ob_get_clean();
    }

}
