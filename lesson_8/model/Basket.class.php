<?php 

Class Basket{

     protected $status = ['status' => false];
     protected $message1 = 'Что-то тут не так =)';
     protected $message2 = 'У вас же корзина пуста, чего вы оформляете?';
     protected $message3 = 'Номер телефона должен начинаться с 8.';
     protected $message4 = 'Вы ввели не существующий телефон.';
     protected $message5 = 'Ваш заказ успешно оформлен. Наш менеджер свяжется с Вами в ближайшее время.';


    public  function index(){
        $cookie = (!empty($_COOKIE['basket'])) ? json_decode($_COOKIE['basket'], 1) : [];
        if (empty($cookie)) return $this->status;
        $tp = 0;
        foreach ($cookie as $key => $cat) {
            foreach ($cat as $key => $p) $tp += $p['price']*$p['count'];
        }
        $cookie['tp'] = $tp;
        return ['basket' => $cookie];
    }

    public  function AddBasket(){
        $cookie = (!empty($_COOKIE['basket'])) ? json_decode($_COOKIE['basket'], 1) : [];
        extract($_POST);
        if (empty($_POST['id']) or !is_numeric($_POST['id'])) return $status;
        $sql = 'SELECT `id`, `catalog`.`name`, `price`, `img`, categories.`name` as `category`  FROM `catalog` 
        INNER JOIN  categories ON category = id_category WHERE id = ?';
        $good =  db::getInstance()->Select($sql, [$_POST['id']]);
        if (empty($good[0])) return ['status' => false, 'message' => 'А ты ждал другого резкльтата?'];
        $good = $good[0];
        extract($good);
        $good['count'] = (!empty($cookie[$category][$id]['count'])) ? ++$cookie[$category][$id]['count'] : '1';
        if($good['count'] > 30) return ['status' => false, 'message' => 'На складе больше нет этого товара'];
        $cookie[$category][$id] = $good;
        setcookie('basket', json_encode($cookie), time()+2592000, '/');
        return ['status' => true];
    }

    public  function RemoveBasket(){
        $cookie = (!empty($_COOKIE['basket'])) ? json_decode($_COOKIE['basket'], 1) : [];
        extract($_POST);
        if (empty($id) or !is_numeric($id) or $action != 'remove') return $this->status;
        foreach ($cookie as $key => $value) {
            if (in_array($id, array_keys($value))) {
                unset($cookie[$key][$id]);
                if (count($cookie[$key]) === 0) {
                    unset($cookie[$key]);
                }
                break;
            }
        }
        $c = (!empty($cookie)) ? json_encode($cookie) : '';
        setcookie('basket', $c, time()+60*60*24*1, '/');
        $_COOKIE['basket'] = $c;
        $count = 0;
        if (!empty($c)) {
            foreach (json_decode($_COOKIE['basket'], 1) as $key => $value) {
                foreach ($value as $key => $c) {
                    $count += $c['count'];
                }
            }
        }
        $tp = $this->index();
        if(!empty($tp['basket'])) return ['tp' => $tp['basket']['tp'], 'id' => $id, 'count' => $count];
        else return ['tp' => false, 'id' => $id];
    }

    public  function Mathematic(){
        $cookie = (!empty($_COOKIE['basket'])) ? json_decode($_COOKIE['basket'], 1) : [];
        extract($_POST);
        if (empty($id) or !is_numeric($id)) return ['status' => 'false', 'message' => $this->message1];
        foreach ($cookie as $key => $value) {
            if (in_array($id, array_keys($value))) {
                if($action == 'plus') ++$cookie[$key][$id]['count']; 
                elseif($action == 'minus') --$cookie[$key][$id]['count']; 
                else return ['status' => 'false', 'message' => $this->message1];
                $pp = $cookie[$key][$id]['count']*$cookie[$key][$id]['price'];
                $c = (!empty($cookie)) ? json_encode($cookie) : '';
                setcookie('basket', $c, time()+2592000, '/');
                $_COOKIE['basket'] = $c;
                $tp = $this->index()['basket']['tp'];
                break;
                }
            }
            return ['status' => true, 'tp' => $tp, 'pp' => $pp, 'id' => $id];

    }

    public  function Order(){
        $cookie = (!empty($_COOKIE['basket'])) ? json_decode($_COOKIE['basket'], 1) : [];
        extract($_POST);
        if(empty($cookie)) return ['status' => false, 'fatal' => false, 'message' => $this->message2];
        $first = (int) substr($phone, 0,1);
        $latter = (int) substr($phone, 1,1);
        if($first !== 8) return ['status' => false, 'fatal' => false, 'message' => $this->message3];
        if(!in_array($latter, [3,4,8,9])) return ['status' => false, 'fatal' => false, 'message' => 'Вы ввели не существующий телефон'];
        if(!preg_match("/^[0-9]{10,11}+$/", $phone))  return ['status' => false, 'fatal' => false, 'message' => 'Какой то странный у Вас телефон =)'];
        if(mb_strlen($name) > 15) return ['status' => false, 'fatal' => false, 'message' => 'Все таки попробуйте ввеси корректное имя.'];
        if(mb_strlen($last_name) > 15) return ['status' => false, 'fatal' => false, 'message' => 'Все таки попробуйте ввеси корректную фамилию.'];
        $tp = $this->index()['basket']['tp'];
        $id = (!empty($_SESSION['user']['id'])) ? $_SESSION['user']['id'] : $name.'_guest';
        $checkselect = 'SELECT * FROM `catalog` WHERE id = ? AND `price` = ?';
        $tp_must = 0;
        foreach ($cookie as $key => $value) {
            foreach ($value as  $g) {
                if($g['count'] < 0) return ['status' => false, 'fatal' => true, 'message' => 'Товар '. $g['name']. ' Почему-то имеет отрицательное занчение.'];
                if($g['count'] == 0) return ['status' => false, 'fatal' => true, 'message' =>  'Товар '. $g['name']. ' вроде добавлен а вроде и нет, как это?'];
                if($g['count'] > 30) return ['status' => false, 'fatal' => true, 'message' => 'Как это у Вас получилось заказать больше чем у нас есть?'];
                $check = db::getInstance()->Select($checkselect, [$g['id'], $g['price']]);
                if(empty($check[0])) return ['status' => false, 'fatal' => true, 'message' => 'У Вас что-то с ценами на товар '.$g['name'].' не то. Проверьте правильность корзины.'];
                $tp_must += $check[0]['price']*$g['count'];
            }
        }
        if($tp_must != $tp) return ['status' => false, 'fatal' => true, 'message' => 'Что-то не то с ценами на товары, нам кажется вы вредитель!'];
        // После всех проверок, оставляем заказ, считаем что с ним все ок.
        $sql = 'INSERT INTO `orders` (`date_order`, `client_id`, `name`,`last_name`,  `phone`, `total_price`) VALUES (NOW(),?,?,?,?,?)';
        $param[]  = [$id, $name, $last_name, $phone, $tp];
        $order =  db::getInstance()->Insert($sql, $param)[0];
        foreach ($cookie as $key => $value) {
            foreach ($value as  $g) {
                $params[] = [$order,$g['id'],$g['count'], $g['price']];
            }
        
        }
        $sql = 'INSERT INTO `basket_goods` (`id_order`, `id_good`, `pcs`, `price`) VALUES (?,?,?,?)';
        $order =  db::getInstance()->Insert($sql, $params);
        setcookie('basket', '', time()-2592000, '/');
        return ['status' => true, 'fatal' => false,  'message' => 'Спасибо '.$name.' '.$this->message5];

    }



}