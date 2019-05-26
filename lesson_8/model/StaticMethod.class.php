<?php 

/**
 * Опишу тут статические методы, которые мне пригождаются по мере работы с сайтом.
 */
class StaticMethod {

    public static function CorrectDate($d){
        $monthes = array(
            1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
            5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
            9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря'
        );
        $format = 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $d);
        $y =  $date->format('Y');
        if($y == '-0001') return '1 Января 1970г.';
        $m = $monthes[$date->format('n')];
        $d = $date->format('d');
         return $d.' '.$m.' '.$y.'г';
    }
    public static function OrderStatus($s){
        switch ($s) {
            case 'open':  $status = ['btn' => 'btn btn-info', 'name' => 'Открыт']; break;
            case 'job':   $status = ['btn' => 'btn btn-primary', 'name' => 'В работе']; break;
            case 'close': $status = ['btn' => 'btn btn-success', 'name' => 'Выполнен']; break;
            case 'cancel': $status = ['btn' => 'btn btn-danger', 'name' => 'Отменен']; break;
            default: $status = ['btn' => 'btn btn-secondary', 'name' => 'Не определен'];
        }
        return $status;
    }

}