<?php

// Трейты

// В папке user->controller создаем новый класс (файл) trait1.php

// Трейты - классы (типа абстракных), к которым можно обращаться из любого другого класса, без прямого наследования
// через use

// Создадим трейт BaseMethods в папке base/controller
protected $styles;
protected $scripts;
// Базовые методы:
protected function init($admin = false){
//  - инициализация стилей и скриптов, которые будут указаны в константах USER_CSS_JS и ADMIN_CSS_JS

    
    if(!$admin){ //если не админ
        if(USER.CSS.JS['styles']){// если существует ячейка styles в константе
            foreach(USER.CSS.JS['styles'] as $item) $this->$styles[] = PATH . TEMPLATE . trim($item, '/');
        }
            //добавить полный пть к файлу в ячейке styles  (приклепить TEMPLATE перед значением) 
        if(USER.CSS.JS['styles']){
            foreach(USER.CSS.JS['scripts'] as $item) $this->$scripts[] = PATH . TEMPLATE . trim($item, '/');
        }
    }
    else{
        if(ADMIN.CSS.JS['styles']){// если существует ячейка styles в константе
            foreach(USER.CSS.JS['styles'] as $item) $this->$styles[] = PATH . TEMPLATE . trim($item, '/');
        }
            //добавить полный пть к файлу в ячейке styles  (приклепить TEMPLATE перед значением) 
        if(ADMIN.CSS.JS['styles']){
            foreach(USER.CSS.JS['scripts'] as $item) $this->$scripts[] = PATH . TEMPLATE . trim($item, '/');
        }
    }
}    

Добавляем трейт в baseController:

    use полный путь с начальным "\"(относительно глобаольного пространства имен) \controller\base\controller\BaseMethods

    Далее создаем методы отчиски строковых и числовых данных

    protected function clearStr($str){
        //делаем проверку 
        if(is_array($str)){ //если на вход массив
            foreach($str as $key->$value){ //проходим его циклом
                $str($key) = trim(strip_tags($item)); //каждое значение по ключу отчищаем от пробелов и тегов
            }
            return $str;
        }
        else{
            return trim(strip_tags($str)); //отчищаем от пробелов и тегов
        }
    }

    protected function clearNum($num){ // при целом и с точкой, даже если в кавычках - выдаст целое и с точкой,
        //при строке - выдаст null
       return $num * 1; 
    }

    protected function isPost(){
        return $_SERVER['REQUEST_METHOD'] == "POST"; // если отправлено с помощью POST - даст true
    }

    protected function isAjax(){ //если запрос придет при помощи xml http request - js метод, его использует
        //  метод библиотеки jq ajax в $_SERVER появится ячейка http x requested width
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function redirect($http = false, $code = false){ //функция для редиректов, сюда приходит код редиректа
        if($code){ //если код пришел
            $codes = ['301' => 'HTTP/1.1 301 Move Permanently']; //формируем массив с кодами

            if($codes[$code]) header($codes[$code]); //если в массиве есть значение пришедшего года - передаем заголовки
        }
        if($http) $redirect = $http; //если есть строка для перенаправления - подставляем ее и делаем перенаправление
            else $redirect = isset($_SERVER['HTTP_REFFERER']) ? $_SERVER['HTTP_REFFERER'] : PATH; 
            // иначе - для редиректа выбираем (если существует) предыдущую страницу, иначе главную

            header("Location: $redirect"); // делаем редирект

            exit;
    }

    protected function writeLog($message, $file = "log.txt", $event = 'Fault'){ //метод логирования, на входе - 
        //сообщение, куда писать и событие

        $dateTime = new \DateTime();

        $str = $event . ':' . $dateTime->format('d-m-y H:i:s') . "-" . $message . "\r\n";

        file_put_contents('log/' . $file, $str, FILE_APPEND);



    }