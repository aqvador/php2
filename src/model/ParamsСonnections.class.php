<?php

/**
 * @info  Подключаемые всевозможные отдельные скрипты для динамических страниц
 * 
 * @param В param  передаем загружаемый модуль. ПО модулю проверяем папки из массива $file
 * @param  Если файлы ф папках есть, то добавляем их в массив
 */
Class ParamsСonnections{

    public static function Check($param){
      $param = str_replace('Controller', '', $param);
      $file = ['css', 'js'];
      $params = [];
      foreach ($file as $key => $value) {
         if(is_file(__DIR__.'/../public/'.$value.'/'.$param.'.'.$value)) {
            $params[$value]['acc'] = 'yes';
            $params[$value]['Сonnections'] = '/'.$value.'/'.$param.'.'.$value;
         }
      }
      return $params;
    }
}