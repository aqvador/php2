<?php

CLass PersonalAccount{

    public static function index(){
        $sql = 'SELECT * FROM orders WHERE client_id = ? ORDER BY id DESC';
        $orders =  db::getInstance()->Select($sql, [$_SESSION['user']['id']]);
        foreach ($orders as $key => $value) {
            $orders[$key]['status'] = StaticMethod::OrderStatus($orders[$key]['status']);
            $orders[$key]['date_order'] = StaticMethod::CorrectDate($orders[$key]['date_order']);
        }
        return $orders;
    }

    public static function Order(){
        $sql = 'SELECT `basket_goods`.`pcs`, `basket_goods`.`price`, `catalog`.`name`, `catalog`.`img`, `catalog`.`id`   
        FROM `basket_goods` 
        INNER JOIN `catalog` ON `basket_goods`.`id_good` = `catalog`.`id`
        WHERE `id_order` = ?';
        extract($_POST);
        $s['modal'] =  db::getInstance()->Select($sql, [$id]); 
        return $s;
    }
}