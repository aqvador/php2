<?php 

Class DesktopController extends Controller {
    public $view;
    public $title;
    public $h1 = 'Рабочий стол';
    function __construct(){
        parent::__construct();
        $this->title = 'Рабочий стол';
        $this->tpl = 'Desktop';
  }
    public function index() {
        return ['slider' => Slider::index()];

    }
}