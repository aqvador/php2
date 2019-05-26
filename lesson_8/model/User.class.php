<?php

Class User{
    private $answer = ['status' => false, 'message' => 'Что-то пошло не так'];
    private static $sol = 'mjNoKVevjr';

/**
 * Авторизация пользователя на сайте
 */
    public function Authorization(){
        if(!empty($_POST) AND $_POST['auth'] === 'auth_yes') {
        foreach ($_POST as $key => $value) $$key = strip_tags($value);
        $password = md5(self::$sol.$password);
        $sql = 'SELECT * FROM `personal`.`users` WHERE `email` = ? AND `pass` = ?';
        $Selectuser =  db::getInstance()->Select($sql, [$email, $password]);
        if(empty($Selectuser[0])) return ['status' => false, 'message' => 'Вы не верно ввели логин или пароль!'];
        $user = $Selectuser[0];
        $_SESSION['user'] = $user;
        $_SESSION['auth_token'] = md5(self::$sol . $email . $password . date('Y-m-d H:i:s'));
        $sql = 'UPDATE  `personal`.`users` SET `token` = ?, eventtime = NOW() WHERE `id` = ? ';
        $param = [$_SESSION['auth_token'], $_SESSION['user']['id']]; //Параметры вставки
        db::getInstance()->Query($sql, $param);
        setcookie("client", json_encode($user), time()+60*60*24*1, '/');
       // setcookie("Авторизация", 'JR', time()+60*60*24*1, '/');
        $this->answer = ['status' => true, 'message' => 'Перенаправляю на главную страницу.'];

        }
        $this->HistiryLogin('in');
        return $this->answer;
}
/**
 * Выход с сайта
 */

public static function logout(){
    $sql = "UPDATE  `personal`.`users` SET `token` = '', eventtime = NOW() WHERE `id` = ? ";
    $param = [$_SESSION['user']['id']]; //Параметры вставки
    db::getInstance()->Query($sql, $param);
    self::HistiryLogin('out');
    $_SESSION = '';
    unset($_SESSION);
    session_destroy();
    return;
}
/**
 * Регистрация нового пользователя на сайте
 */

public static function registration(){
    if($_POST['registr'] != 'registration_yes') die();
    foreach ($_POST as $key => $value) $$key = strip_tags($value);
            /**
         * вдруг проник плохой Email
         */
        if($email != $_POST['email'] OR empty($email)) return ['status' => false, 'message' => 'У вас что-то не то с Email'];
                /**
         * вдруг проник плохой пароль
         */
        if($password != $_POST['password'] OR empty($password)) return ['status' => false, 'message' => 'У вас что-то не то с паролем'];
        /**
         * А вдруг в телефоне что то не то.
         */
        $first = (int) substr($phone, 0,1);
        $latter = (int) substr($phone, 1,1);
        if($first !== 8) return ['status' => false, 'message' => 'Номер телефона должен начинаться с 8.'];
        if(!in_array($latter, [3,4,8,9])) return ['status' => false, 'message' => 'Вы ввели не существующий телефон'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) !== $email) return ['status' => false, 'message' => 'Вы ввели не корректный emil'];
    if($password !== $confirmpassword)  return ['status' => false, 'message' => 'Увы! Пароли не совпали'];
    $sql = 'INSERT INTO `users` (`name`, `last_name`, `phone`, `email`, `pass`) VALUES (?, ?, ?, ?, ?)';
    $password = md5(self::$sol.$password);
    $param = [$name, $last_name, $phone, $email, $password]; //Параметры вставки
    db::getInstance()->Query($sql, $param);
    return ['status' => true, 'message' => 'Здравствуйте '.$name.' теперь вы можете авторизоваться!'];

}
/**
 * Запись в лог всех входов выходов пользователя
 */

public static  function HistiryLogin($type){
    extract($_SESSION['user']);
    logger::write($type);
    $ip = $_SERVER['REMOTE_ADDR'];
    $sql = 'INSERT INTO `history_user` (`name`, `email`, `IP`, `eventtime`, `type`) VALUES (?, ?, ?, NOW(), ?)';
    $param = [$name, $email, $ip, $type];
    db::getInstance()->Query($sql, $param);
    return;
}
/**
 * Проверка занятого email  при регистрации.
 */
    public static function CheckMail(){
    $sql = 'SELECT * FROM `personal`.`users` WHERE `email` = ?';
    $param = [$_POST['email']];
    $user =  db::getInstance()->Select($sql, $param);
    return  (count($user) > 0 ) ?  false : true;
    }
/**
 * Проверка подленности авторизованного юзера. 
 * Если не подлинный то выкидываем на 404 или на авторизацию.
 */
    public static function SetUser(){
        session_start();
        if (!empty($_SESSION['auth_token'])) {
            $sql = 'SELECT * FROM `personal`.`users` WHERE token = ?';
            $user = db::getInstance()->Select($sql, [$_SESSION['auth_token']]);
            if (!empty($user[0])) {
                if ($_SESSION['auth_token'] != $user[0]['token']) { // Если токен пользователя не совпадает с нужным
                    $_GET['route'] = 'authorization';
                    $_SESSION = [];
                    return;
                } else $_SESSION['user'] = $user[0]; // Правильный Вариант.
            } else {
                $_GET['route'] = 'authorization';
                $_SESSION = [];
            }
        }
    }


}