<?php

class CatalogController extends Controller {
    public $view = 'catalog';
    public $h1 = 'Каталог товаров';

    function __construct(){
        parent::__construct();
          $this->title = 'Каталог товаров | Каталог';
    }
    // главная страница Каталога
    public function index() {
        $this->tpl = 'Catalog';
        return ['catalog' => Catalog::getCatalog()];
    }
    public function ajaxGetCatalog() {
        $this->tpl = 'Sneakers';
        if(!is_numeric($_POST['count_show']) OR !is_numeric($_POST['count_add'])) die();
        extract($_POST);
        $data =  Catalog::getCatalog($category, $count_show, $count_add);
        $int = count($data);
        if (!empty($data)) {
            $this->param = ['status' => true, 'show' => $count_show+$int, ];
            return  ['catalog' => $data];

        }
    }
    public function Detailed($param){
        $this->tpl = 'Detailed';
        $a =  Catalog::Detailed($param);
        if(count($a) === 0) return $this->Page404();
        else return $a;
    }

    public function test(){
        //Вызов метода 404 страница. работает огонь!
        $this->input =  $this->Page404();
    }

    public function Category($param){
        $this->tpl = 'Catalog';
        $this->title = 'Каталог товаров | ';
        $a = Catalog::Category($param);
        if(!$a)  $this->input =  $this->Page404();
        $this->title .= $a['h1'];
        return ['catalog' => $a['content']];
    }

}