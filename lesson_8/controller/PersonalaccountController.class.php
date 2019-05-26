<?php 

class PersonalaccountController extends Controller{
    public $h1 = 'Личный Кабинет';
    public $view = 'Personal';
    function __construct(){
        parent::__construct();
        $this->title = 'Персональный личный кабинет';
        $this->tpl  = 'Personalaccount';
  }

    public function index(){
        if(empty($_SESSION['auth_token'])) return $this->Page404();
        return PersonalAccount::index();
    }
    public function Order(){
        $this->tpl = 'ContentModal';
        $this->param = ['status' => true, 'id' => $_POST['id']];
        return PersonalAccount::Order();


    }
}

