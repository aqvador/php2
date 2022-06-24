<?php
/**
 * @info Меню сайта 
 * @param Индекс menu  строит основное меню слева
 * @param Индекс form  строит меню справа
 * @param Последнему элементу массива form  будет присвоена красная иконка
 * @param Всем авторизованным пользоватерям показывается лисный кабинет
 * @param Всем Админам показывается админка
 * 
 * @return $menu array menu
 */

Class Submenu{
    public static function index(){

        $menu = ['menu' => [
                    ['name' => 'Главная', 'link' => '/',        'icon' => 'fa fa-home'],
                    ['name' => 'Каталог', 'link' => '/catalog', 'icon' => 'fa fa-book']
                ],
                'catalog' => db::getInstance()->Select('SELECT * FROM categories Where `status`=1 ORDER BY id_category'),

                'form' => [
                    ['name' => 'Вход',      'link' => '/authorization', 'icon' => 'fa fa-sign-out',     'id' => 'authorization']
                ]
        ];
            if(!empty($_SESSION['auth_token'])) {
                $name = $_SESSION['user']['name'];
                $menu['form'][0] =  ['name' => 'Выход ('.$name.')', 'link' => '/authorization/out', 'icon' => 'fa fa-blind'];
                $menu['menu'][] =   ['name' => 'Личный кабинет',    'link' => '/personalaccount',   'icon' => 'fa fa-shirtsinbulk'];

                if ($_SESSION['user']['role'] == 'admin') {
                    $menu['menu'][] = ['name' => 'Админка', 'link' => '/administration', 'icon' => 'fa fa-male'];
                }
            }
            $name = 'Корзина';
                if(!empty($_COOKIE['basket'])) {
                    $count = 0;
                    foreach (json_decode($_COOKIE['basket'], 1) as $key => $value) {
                        foreach ($value as $key => $c) {
                            $count += $c['count'];
                        }
                    }
                    
                    $name = 'Покупок ' . $count;
                }
                $menu['form'][] = ['name' => $name, 'link' => '/basket', 'icon' => 'fa fa-shopping-bag', 'id' => 'basket'];
        return $menu;
    }
}