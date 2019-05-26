<?php
/**
 *  Роутинг сайта Class Router
 *
 *  @param GET В параметре GET  содержится информация по запускаемым классам и методам
 *  @param GET Запрос гет делится на части. где первая часть это Class он же Контроллер  врторая часть Method
 *  @param GET после второго влеш могут содержаться разные параметры зпроса к методу
 * 
 *  @return  Возаращает  найденные методы, классы, параметры если их нет то страницу NotFount
 *  @return  Если в GET  запросе пусто. выдает страницу NotFount 404
 *
 *  @author aqvador
 */
Class Router {
    protected $params;
    protected $type = 'default';
    
    public function start() {
        User::SetUser(); // Проверка подлинности юзера.
        $this->getController();
    }
    public function GetVarName($var) {
        if(!empty($this->$var)) return $this->$var;
    }
    // определение контроллера и экшена из урла
    private function getController() {
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
        unset($_GET['route']);
        //Страница по умолчанию
        if (empty($route)) $route = 'Desktop';
         
        // Получаем части урла
        $this->route = trim($route, '/\\');
        $parts = explode('/', $this->route);
        //Определяем контроллер
        $controller = (!empty($parts[0])) ? ucfirst(strtolower($parts[0])) : 'NotFound';
        // Если в качестве контроллера пришел  Ajax, обработаем его по иному
        if($controller == 'Ajax'){
            $this->MethodAjax();
            return;
        }
        array_shift($parts);
        // Определяем метод
        $method = (!empty($parts[0])) ? $parts[0] : 'index';
        array_shift($parts);
        //Определяем параметры метода если нужны
        if(count($parts) > 0) $this->params = $parts;

        //Пробуем найти путь к запрашиваемому контроллеру, классу
           if(gbStandardAutoload($controller . 'Controller')) $this->controller =  $controller . 'Controller';
           elseif(gbStandardAutoload($controller)) $this->controller = $controller;
           else $this->controller = 'NotFound';

        //Определяем, есть ли указанный метод. если нет, то на 404
        $obj = new $this->controller;
        if(!method_exists($obj, $method)) {
            $this->controller = 'NotFound';
            $this->method = 'index';
        } else $this->method = $method;
    }
     
 

    private function MethodAjax(){
        /*
        * ajax это конечно хорошо. но с пустым запросом POST  тут делать не чего =)
        * по этому выставляем значения для страницы NotFound и выходим из скрипта
        */
       
        if(empty($_POST)) {
            Logger::write($this->route, 'Попытка проникнуть по Ajax');
            die();
        }
        $parts = explode('/', $this->route);
        // Сразу удалим ajax  из массива
        array_shift($parts);
        // присвоим контроллер
        $controller = (!empty($parts[0])) ? ucfirst(strtolower($parts[0])) : '';
        array_shift($parts);
        // если контроллера нет то завершаемся
        if(empty($controller)) die();
        //определяем метод, если  его нет, то ставим по умолчанию Outer
        $this->method = (!empty($parts[0])) ? $parts[0] : 'Outer';
        array_shift($parts);
        // Узнаем есть ли такой класс и подключим его если он определен
        if(gbStandardAutoload($controller . 'Controller')) $this->controller =  $controller . 'Controller';
        else if(gbStandardAutoload($controller)) $this->controller =  $controller;
        else Logger::write($controller, 'Вылет с контроллера');
        // Сставшееся в массиве, положим в мараметры.
        if(count($parts) > 0) $this->params = $parts;
        $this->type = 'ajax';
    }   
}


