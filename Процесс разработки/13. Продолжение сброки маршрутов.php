<?php


// метод inputData() для сборки данных из бд или файловой системы предпримет действия и передаст данные
// следующему методу outputData - через свойства

protected $name;

protected function inputData(){
    $this->name = "masha"; // - это переменная, а не свойство класса, доступ к ней есть только в рамках функции
}

protected function outputData(){
    // теперь св-во доступно из inputData
}

Переделаем взаимодействие inputData и outputData

protected function inputData(){
    Определяются переменные с какой то инфой для вывода:
    $name = 'Masha';
    $sur = 'Ivanova';

    return compact('name', 'sur'); // создает массив ключ - значение с данными из переменных
}
protected function outputData(){
    $vars = func_get_arg(0);
    exit($this->render('', $vars);
}
А в public function request($args){
        
    $this->parameters = $args['parameters'];
    $inputData = $args['inputMethod']; //тут берется название метода из настроек и потом по этому имени метод запрашивается 
    //у самого объекта this(с таким же названием)
    $outputData = $args['outputMethod'];

    $data = $this->$inputData();
    //для того, чтобы выводить инфу 1-м методом, упрощенно, если информацию мы уже сформировали:
    if(method_exists($this, $outputData)){ //если существует в IndexController метод , название которого в переменной $outputData
        $this->page = $this->$outputData($data);
    }elseif($data){ //мы можем не возвращать $data, если $page уже сформирован в inputData
        $this->page = $data;
    }

    

    if($this->errors){
        $this->writeLog();
    }
    $this->getPage();
    

}
protected function getPage(){
    if(is_array($this->$page))   //если придет массив, то выводим все элементы
        foreach ($this->page as $block) echo $block;
    }else{
        echo $this->page;
    }
    exit();
 }


//  тест:
// 1. Вариант
//  в inputData формируем переменные и в итоге массив с данными и выдаем его на вывод
 $name = 'Vasya';
 $content = $this->render('', compact('name'));
 $header = $this->render(TEMPLATE . 'header');
 $footer = $this->render(TEMPLATE . 'footer');

//  далее в outputData мы принимаем данные и выдаем их на выход:
 $vars = func_get_arg(0);
        
 return $vars;

//  в this->page попадет массив и get_page с помощью foreach выдаст весь контент

// 2. Если есть шаблонизатор

// создаем файл templater.php
// и в нем ожидаем прихода переменных

<div>Это метополя</div>
<?=$header
<div>Контент страницы</div>
<?=$content?>
<div>Продолжение страницы</div>

<?=$footer?>

а в private function outputData(){
    $vars = func_get_args(0);

    $this->page = $this->render(TEMPLATE . 'templater', $vars) //Обрабатываем данные, но не выводим!

    Т.к. на выходе null, меняем ранее написанный код:

    if(method_exists($this, $outputData)){ //если существует в IndexController метод , название которого в переменной $outputData
        $page = $this->$outputData($data); //объявляем переменную для проверки данных, которые возвращаются с метода
        //outputData, тк может вернутся null и не выведется ничего
        if($page){
            $this->page = $page;
        }
    }elseif($data){ //мы можем не возвращать $data, если $page уже сформирован в inputData
        $this->page = $data;
    }

}
//3 вариант: 
//убрать this->outputData

// Тогда вернется информация с inputData, без обработки через условие  
// elseif($data){ а в $data - результат функции inputData
//     $this->page = $data;
// }

// Далее необходимо доработать метод render для использования нескольких вариантов шаблонов (по дефолту сейчас user шаблоны)

protected function render($path = '', $parameters = []){

    extract($parameters);

    if(!$path){

        $class = new \ReflectionClass($this); //пытаемся получить доступ к путям шаблонов классов (админ и user части сайта)
        $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');//пространство имен для класса,
        //  объект которого - $this, тут сейчас строка админ или юзер части 
        $routes =   Settings::get('routes');

        if($space === $routes['user']['path']){ //если namespace класса (админ или юзер) = пути в настройках для user
            $template = TEMPLATE;
        }
        else($space === $routes['admin']['path']){
            $template = ADMIN_TEMPLATE; //плагин будет использовать шаблоны административной части
        }

        $path = $template . explode('controller', strtolower($class->getShortName()))[0];
    }
    ob_start();
    if(!@include_once($path . '.php')){
        throw new RouteExсeption('Не верный путь к шаблону - ' . $path);
    }

    return ob_get_clean();
}