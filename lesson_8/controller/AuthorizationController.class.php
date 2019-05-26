<?php 
/**
 * @info Авторизация и регистрация пользователей на сайте
 * 
 * @author aQvadOr
 */

Class AuthorizationController extends Controller {
    
    public $h1 = 'Страница авторизации и регистрации';
    private $user, $pass;


    function __construct(){
        parent::__construct();
        $this->title = 'Страница авторизации';
  }
/**
 * Запуск главного метода, отрисовка страницы
 */
    public function index(){
        $this->view = 'auth';
        $this->tpl = 'Authorization';
        return;
    }
/**
* Метод авторизации пользователя
*/
    public function auth(){
        $this->view = '';
        
        $a = new User;
        return json_encode($a->Authorization());
    }
/**
 * Метод выхода пользователя из системы
 */
    public function out(){
        $this->input = '';
        User::logout();
        header("Location: /");
    }

/**
* Регистрация нового пользователя
*/
    public function registr(){
        $this->view = '';
        return json_encode(User::registration());
        
    }
/**
* Проверка email  на существующий при регистрации
*/
    public function CheckMail(){
        return json_encode(User::CheckMail());
    }

}