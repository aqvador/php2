<?php

/**
 * @method __construct Принимает ошибку и устанавливает статическое свофство fatal
 * @method __construct Если ошибка есть то  передает управление в метод read
 * 
 * @method read Метод щзаписи ошибки в лог и вывода страницы 404  на экран
 * 
 * @return Отдает страницу 404 в случае фатальной ошибки.
 * 
 * @author aqvador
 */

class FatalError{

    protected static $fatal;
    protected  $code;
    
    public function __construct($e, $code){
        $this->fatal=$e;
        $this->code = $code;
        if($e != '') $this->read();
    }

    private function read(){
    foreach($this->fatal as $key => $i){
        $html .= $key . ' '. $i . "\n";
    }
    if($this->code === 16384 OR $code === 8192) return;
    Logger::write($html, 'Фатальная Ошибка', false);
    $controller = new NotFound;
    $data = [
        'content_data' => $controller->index(),
        'h1' =>  $controller->h1,
        'title' => $controller->title,
        'param' => ParamsСonnections::Check($controller->ParamsConnect)
    ];
                $tpl = $controller->view . '/' . $controller->tpl . '.html';
                $template = (is_file(__DIR__.'/../tpl/'.$tpl)) ? $tpl : false;
                $loader = new Twig_Loader_Filesystem(Config::get('path_templates'));
                $twig = new Twig_Environment($loader);
                if($template) $controller->input = $twig->render($template, ['data' => $data]); // Рисуем страницу
                echo $controller->input;
        die();
    }
}