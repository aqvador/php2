<?php

Class AdministrationController extends Controller {
  public $h1 = 'Админочка';
  public $view = '/Administration';
  public $tpl = 'Administration';
  function __construct(){
    parent::__construct();
      $this->title = 'Администратор сайта';
}
  public function index(){
    if(empty($_SESSION['auth_token']) OR $_SESSION['user']['role'] !== 'admin') return $this->Page404();
    $a = new Administration;
    return $a->GetOrders();

  }
  public function SetRole(){
    $this->tpl = '';
    return Administration::SetRole();
  }

  public function GetAjaxContent(){
    $this->tpl = '';
    if(empty($_POST['action'])) return false;
    $a = new Administration;
    if ($_POST['action'] != 'orders') return json_encode(['status' => '404']);
    $action = 'Get'.$_POST['action'];
    return json_encode($a->$action());
  }

  public function OrderEditStatus(){
    $this->view = 'Alert';
    $this->tpl = 'BootstrapAlert';
    $a =  Administration::OrderEditStatus();
    $this->param = $a['alert']['param'];
    return $a;

  }

  public function GetAjaxStatusOrder(){
    $this->tpl = 'AjaxOrder';
    $a['orders'] =  Administration::GetAjaxStatusOrder();
    $this->param = ['status' => true];
    return $a;
  }

  public function ShowOrder(){
    $this->tpl = 'ContentModal';
    $this->view = 'Personal';
    $this->param = ['status' => true, 'id' => $_POST['id']];
    return PersonalAccount::Order();


}

}