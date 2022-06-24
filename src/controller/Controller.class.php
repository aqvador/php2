<?php
/**
 * @info Главный контроллер сайта
 * 
 * @param Главный нюанс запросы ajax. есть некоторые правила. 
 * @param 1. Все запросы ajax должны приходит на начинающийся url /ajax
 * @param 2. Все запросы ajax  должны содержать POST параметры. и только POST.
 * @param 3. Если надо отрисовать страницу, в методе надо указать $this->tpl = шаблон для отрисовки. а иначе ставим $this->tlp = ''
 * 
 * @param  Если надо вернуть страницу 404  из Контроллера вызываем $this->Page404()
 * 
 * @param ParamsConnect Служит для подключения нужных скриптов и css  к разным страницам, если они существуют в папке, то будут подключены автоматически
 * 
 * @author aQvadOr
 * 
 */

abstract class Controller{
   public $view, $tpl, $title, $h1, $input;

    function __construct(){
        $this->title = Config::get('sitename');
        $this->ParamsConnect = str_replace('Controller', '', get_class($this));
    }
/**
 * Метод по умолчанию. Переопределяется в каждом классе
 */
    abstract function index();
/**
 * Получение защищенных свойств контроллера
 */
    public function GetVarName($var) {
        if(!empty($this->$var)) return $this->$var;
    }
/**
 * @info Страница 404 , вызывается из контроллера $this->Page404()
 */
    public  function Page404(){
        $page = new NotFound;
        $this->tpl      = $page->tpl;
        $this->title    = $page->title;
        $this->view = '';
        $this->ParamsConnect = $this->tpl;
        return;
    }
}