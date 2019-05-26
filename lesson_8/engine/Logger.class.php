<?php

/**
 *  Приложение вывода сообщений наэкран и записи а лог
 *
 *  @param message Сообщение выводимое на экран
 *  @param echo параметры true  и false, для вывода на экран
 * 
 *  @return  string записывает в файл лога и при echo == true выводит сообщение на экран.
 *  
 * @author aqvador
 */

class Logger {

    public static function Write($message, $title = 'DEBUG', $echo = false){
        $log = "\n------------------------\n"; 
        $log .= date("Y.m.d G:i:s") . "\n"; 
        $log .= 'Titte: ' . (strlen($title) > 0 ? $title : 'DEBUG') . "\n"; 
        $log .= 'Message: ' . print_r($message, 1); 
        $log .= "\n------------------------\n"; 
        file_put_contents(__DIR__.'/../log.txt', $log, FILE_APPEND); 
        if ($echo) echo $log . "\n";
    }    
}