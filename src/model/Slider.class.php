<?php

Class Slider{

    public static function index() {
        $dir = __DIR__.'/../public/img/slider/photo/';
        return array_diff(scandir($dir), array('..', '.'));
         
    }
}