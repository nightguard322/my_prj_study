<?php
//Если до начала значения ключа alias в массиве admin в строке 
//$address_str кол-во символов = длине PATH
if(strpos($address_str, $this->routes['admin']['alias'] ) === strlen(PATH)){

   $url = explode('/', substr($address_str, strlen(PATH . $this->routes['admin']['alias']) + 1)); 
   //1.обрезаем строку от корня (PATH) + слово (aliace) admin + "/"

   //2.Далее, т.к. плагины будут лежать по адресу host/admin/plugin/controller/далее, нужно проверить, не лежит ли
   // в 0-м элементе плагин
   if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){
    //Если 0 элемент существует и если есть директория для него с таким же именем по пути:
    //$_SERVER['DOCUMENT_ROOT'](корневая папка)->path(корень сайта)->настройка,
    // в которой путь к папке с плагинами-> название папки плагина

        $plugins = array_shift($url); // вытаскиваем название плагина и массив нумеруется снова с 0

        $pluginSettings =  $this->routes['settings']['path'] . usfirst($plugin . 'Settings'); //ищем настройки плагина. Настройки должны называться
        //с большой буквы и к нему прикреплено название Settings
        if(file_exists($S_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')){
            
        }

   }else{
        $this->controller = $this->routes['admin']['path']; //в св-во конт-р добавляем путь с настроек

        $hrUrl = $this->routes['admin']['hrUrl']; //Проверка, исп-ся ли ЧПУ

        $route = 'admin';
   }



}else{
    $url = explode('/', substr($address_str, strlen(PATH)));
}