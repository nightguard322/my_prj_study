<?php

use core\base\controller\BaseController;
use core\base\settings\Settings;
use core\admin\models\Model;

//Вместо index создаем дефолтный контроллер вывода админки (индекс оставим для статистики)
    //унаследуем его также от BaseContoller, а от админкского унаследуем контроллер вывода чего-либо
    //Это контоллер будет подключаь хедер и футер

    abstract class BaseAdmin_l24 extends BaseController
{

    protected $model; //обьект класса модели (для пользования ее методами)
   
    protected $table; //таблицы в запросе (какие таблицы показать)
    protected $columns; //колонки таблиц в запросе
    
    protected $menu;  // для левого меню сайта
    protected $title; //Заголовок

    protected function inputData()
        {//инициализируем (записываем) пути до стилей и скриптов

        $this->init(true); //true = admin

        $this->title = 'Test CMS';

        //проверяем, сохранен ли где то уже обьект модели

        if(!$this->models) $this->model = Model::instance();
        if(!$this->menu) $this->menu = Settings::get('projecTables'); //боковое меню

        //Далее формируем механизм отмены кеширования данных загрузки (файлов) в админпанели

        $this->sendNoCacheHeaders();
        }
        protected function sendNoCacheHeaders()
            { //отправляет заголовки браузеру (что делать)

            header("Last-Modified: " . gmdate("D, d m y H:i:s")); //Last-Modified отправляет заголовок ответа последней
            // модификации контента (загружать ли контент), а время придет после отправки запроса, браузеру будет команда загрузить
            //контент, gmdate - отправляет дату по гринвичу

            //далее заголовки для разных браузеров (тк все обрабатывают заголовки по разному)
            header("Cache-Control: no-cache, must-revalidate"); //принуждает модуль браузера, отвечающий за кэш отправить запрос
            //на сервер каждый раз для валидации данных хранящихся в кэше
            header("Cache-Control: max-age=0"); //максимальный период актуальности контента ( в нашем случае каждый раз грузить заного)
            header("Cache-Control: post-check=0, pre-check=0"); //для IE, post-check необходимо проверить данные после загрузки,
            //pre-check проверить данные перед их показом
      
            }

            protected function execBase()
            {
                self::inputData();
            }
}
//Далее в ShowController (контроллер показа контента, любого, который туда приходит)

//namespace: указать
class showController_l24 extends BaseAdmin_l24{

    protected function inputData()
    {
        //вызываем метод родителя
        $this->execBase();
    }

    //Т.к. у нас далее будут плагины и наследоваться они будут от класса showController - в этом случае нам не нужно выполнять
    //inputData() класса BaseAdmin, поэтому создадим функцию, которая будет вызывать метод inputData() у BaseAdmin и 
    //вызываем его тут


    //далее при выводе инфы через контроллер show мы должны подать параметры ( а админка без ЧПУ) , и вместо параметров /table/teachers/id/5
    //мы должны организовать /teachers/5

    //создаем метод в BaseAdmin


    //Определяем, в какой таблице работаем
    protected function createTableData()
    {
        if(!$this->table){ //если не заданы параметры таблицы
            if($this->parameters) $table = array_keys($this->parameters)[0]; //если есть массив параметров то определяем table
            //как первый ключ в массиве параметров
                else $this->table = Settings::get('defaultTable'); //иначе дефолтная таблица из настроек
        }

        $this->columns = $this->model->showColumns(); //определяем колонки

        if(!$this->columns) throw new RouteExeption('Не найдены поля в таблице - ' . $this->table, 2); //если не попали колонки 
        //выбрасываем исключение
    }


    //Описываем метод сбора данных для шаблона вывода
    //Опишем св-во в котором будем хранить основные данные в BaseAdmin -> protected $data;


   protected function createData(array $arr = [], bool $add = true)  //Если add = true, то $arr добавляем к базовому запросу
   {

    //Что нужно получить из бд: id, для начала переименуем поле с id родителя в parent_id в дочерней таблице и создадим поле
    //для сортировки (menu_position) - для нее потом создадим метод для перестроения, если сместится другой элемент
    //

   }
   
   

}   



