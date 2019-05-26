<?php

Class Administration {

  public static function GetUser(){
    $sql = 'SELECT * FROM `personal`.`users`';
    return db::getInstance()->Select($sql);
  }
  public static function SetRole(){
    if($_SESSION['user']['role'] !== 'admin') die();
    if($_POST['id'] == 153 AND $_POST['role'] == 'admin') return json_encode(['status' => true, 'message' => 'Будем считать что ты меня повысил =))']);
    if($_SESSION['user']['id'] == $_POST['id']) return json_encode(['status' => false, 'message' => 'Ты тут не решаешь сам за себя']);
    if($_POST['id'] == 153) return json_encode(['status' => false, 'message' => 'Ты не сможешь меня разжаловать =)']);
    foreach ($_POST as $key => $value) $$key = strip_tags($value);
    if(!is_numeric($id)) return json_encode(['status' => false, 'message' => 'Что-то ты не так делаешь =)']);
    $sql = "UPDATE `users` SET `role` = ? WHERE id = ?";
    $a = db::getInstance()->Query($sql, [$role, $id])->rowCount();
    if($a === 1) return json_encode(['status' => true, 'message' => 'Успешно обновлено']);
    else  return json_encode(['status' => false, 'message' => 'Что-то ты не так делаешь =)']);
  }

  public static function GetOrders($param = 'open'){

    $sql = 'SELECT * FROM `orders` WHERE `status` = ?';
    $orders =  db::getInstance()->Select($sql, [$param]);
    foreach ($orders as $key => $value) {
      $orders[$key]['status'] = StaticMethod::OrderStatus($orders[$key]['status']);
      $orders[$key]['date_order'] = StaticMethod::CorrectDate($orders[$key]['date_order']);
    }
    return $orders;

  }

  public static function OrderEditStatus(){
    extract($_POST);
    if(empty($id) OR empty($status)) die();
    $sql = 'UPDATE `orders` SET `status` = ? WHERE `id` = ?';
    logger::write($sql);
    logger::write($_POST);
     $row = db::getInstance()->Query($sql, [$status, $id])->rowCount();
     if ($row === 1) {
         $a['alert']['h1'] = 'Заказ #' . $id;
         $a['alert']['text'] = 'Успершно обновлен статус!';
         $a['alert']['type'] = 'alert alert-success';
         $a['alert']['param'] = ['id' => $id, 'mesid' => $id.$status.time(), 'status' => true];
     } else {
      $a['alert']['h1'] = 'Заказ #' . $id;
      $a['alert']['text'] = 'Статус не изменился';
      $a['alert']['type'] = 'alert alert-danger';
      $a['alert']['param'] = ['id' => $id, 'mesid' => $id.$status.time(), 'status' => false];
     }
    return $a;
  }

  public static function GetAjaxStatusOrder(){
    extract($_POST);
    if(empty($who)) die();
    if ($who == 'all') {
        $sql = 'SELECT * FROM `orders`';
        $orders = db::getInstance()->Select($sql);
    } else {
        $sql = 'SELECT * FROM `orders` WHERE `status` = ?';
        $orders = db::getInstance()->Select($sql, [$who]);
    }
    foreach ($orders as $key => $value) {
      $orders[$key]['status'] = StaticMethod::OrderStatus($orders[$key]['status']);
      $orders[$key]['date_order'] = StaticMethod::CorrectDate($orders[$key]['date_order']);
    }
    return $orders;


  }

  public static function ShowOrder(){
    $sql = 'SELECT `basket_goods`.`pcs`, `basket_goods`.`price`, `catalog`.`name`, `catalog`.`img`, `catalog`.`id`   
    FROM `basket_goods` 
    INNER JOIN `catalog` ON `basket_goods`.`id_good` = `catalog`.`id`
    WHERE `id_order` = ?';
    extract($_POST);
    $s['modal'] =  db::getInstance()->Select($sql, [$id]); 
    return $s;
}
}