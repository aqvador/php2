<?php

class NotFound extends Controller {
    public $view = '';
    public $title;

    function __construct(){
          $this->title = 'Страница не найдена';
          $this->tpl = 'NotFound';
          $this->ParamsConnect = 'NotFound';
    }
    // главная страница 404
    public function index() {
        return true;
    }

}