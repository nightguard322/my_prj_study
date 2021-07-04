<?php

namespace core\base\controller;


use core\base\settings\Settings;
use core\base\settings\ShopSettings;
use core\base\exсeptions\RouteExсeption;

class RouteController
{
    static private $_instance;
    protected $routes;

    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    public function __clone(){
            
    }

    private function createRoute($var, $arr){
        $route = [];

        if(!empty($arr[0])){

            if($this->routes[$var]['routes'][$arr[0]]){

                print_arr($this->routes[$var]['routes'][$arr[0]]);
                
                $route = explode("/", $this->routes[$var]['routes'][$arr[0]]);

                $this->controller .= ucfirst($route[0] . 'Controller'); 
                echo "Маршрут найден";
            
            }else{
                
                $this->controller .= ucfirst($arr[0] . 'Controller'); 
                echo "Маршрут взят со строки браузера";
            }
        }else{
            echo "Маршрут дефолтный";
            $this->controller .= $this->routes['default']['controller']; 
        }

        $this->inputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];

        return;
    }

    static public function getInstance(){
        if(self::$_instance instanceof self){
            return self::$_instance;
        }

        return self::$_instance = new self; 
    }

    public function __construct()
    {
        $adress_str = $_SERVER['REQUEST_URI'];
        
        if((strrpos($adress_str, '/') === strlen($adress_str) - 1) && strrpos($adress_str, '/') !== (strlen(PATH) - 1))
            // $this->redirect(rtrim($adress_str, '/'), 301);
            echo "делаем редирект";
        
        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));

        if($path === PATH){

            $this->routes = Settings::get('routes');

            if(!$this->routes) throw new RouteExсeption('Сайт находится на техническом обслуживании');
                
            echo 'это равно - ' . strpos($adress_str, 'admin') . ', а '; 
            
            echo 'хрень = ' . strlen(PATH);
            if(strpos($adress_str, $this->routes['admin']['alias'] ) === strlen(PATH)){
               
                $url = explode('/', substr($adress_str, strlen(PATH . $this->routes['admin']['alias']) + 1)); 

                if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){
                    
                    $plugin = array_shift($url);

                    $pluginSettings = $this->routes['plugins']['path'] . ucfirst($plugin . 'Settings');
                    
                    echo  $pluginSettings;
                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')){
                        echo 'мы где надо';
                        
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');

                        $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '.' : "/";
                        $dir = str_replace("//", '/', $dir);

                        $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                        
                        $hrUrl = $this->routes['plugins']['hrUrl']; 

                        $route = "plugins";
                        
                    }

                    
                }else{
                    echo 'мы в жопе';
                    $this->controller = $this->routes['admin']['path'];

                    $hrUrl = $this->routes['admin']['hrUrl'];

                    $route = "admin";   

                }

            }else{
                echo 'мы в полной жопе';
                $url = explode('/', substr($adress_str, strlen(PATH)));

                $hrUrl = $this->routes['user']['hrUrl'];
                

                $this->controller = $this->routes['user']['path'];

                $route = 'user';
           
            }

            $this->createRoute($route, $url);

        //Если до начала значения ключа alias в массиве admin в строке $address_str кол-во символов = длине PATH
            if($url[1]){
                $count = count($url);
                $key = '';
                
                if(!$hrUrl){
                    $i = 1;
                }
                else{
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }
                for(;$i < $count; $i++){
                    if(!$key){
                        $key = $url[$i];
                        $this->parameters[$key] = '';
                    }
                    else{
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }
                exit();
        }
        
        

        else{
            try{ 
                throw new \Exception('Некорректная директория сайта');
            }
            catch(\Exception $e){
                exit($e->getMessage());
            }
        }
        
    }

}
