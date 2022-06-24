<?php

/**
 *  Главное приложение сайта
 *
 * @method Init При инициализации приложения, устанавливается тайм зона по умолчанию и подключение к БД
 * @method web Далее вызывается метод web  для определения запрошенных параметров для отображения
 * @method web На основании Объекта Router происходит определение вызванных классов и методов
 *
 * @return Возвращает в переменную $controller->input отрисованную страницу
 *
 * @author aqvador
 */

class App
{

    public static function Init()
    {

        date_default_timezone_set('Asia/Yekaterinburg');
        db::getInstance()->Connect(Config::get('db_user'), Config::get('db_password'), Config::get('db_base'), Config::get('db_host'));
        if (php_sapi_name() !== 'cli' && isset($_SERVER) && isset($_GET)) {
            self::web();
        } else die();
    }

    protected static function web()
    {
        $route = new Router;
        $route->start();
        $class = $route->GetVarName('controller');
        $method = $route->GetVarName('method');
        $params = $route->GetVarName('params');
        $controller = new $class;
        if ($route->GetVarName('type') != 'ajax') {
            $data = [
                'content_data' => $controller->$method($params),
                'h1' => $controller->h1,
                'menu' => Submenu::index(),
                'title' => $controller->title,
                'param' => ParamsСonnections::Check($controller->ParamsConnect),
                'basket' => (empty($_COOKIE['basket'])) ? 0 : json_decode($_COOKIE['basket']),
                'session' => (!empty($_SESSION)) ? $_SESSION : ''
            ];
        } else $data = ['content_data' => $controller->$method($params)];
        $tpl = $controller->view . '/' . $controller->tpl . '.html';
        $template = (is_file(__DIR__ . '/../tpl/' . $tpl)) ? $tpl : false;
        $loader = new Twig_Loader_Filesystem(Config::get('path_templates'));
        $twig = new Twig_Environment($loader);
        if ($template) $controller->input = $twig->render($template, ['data' => $data]); // Рисуем страницу

        if ($route->GetVarName('type') == 'ajax') {
            if (!$template) $controller->input = $data['content_data'];
            else $controller->input = json_encode(['data' => $controller->input, 'param' => $controller->param]);
        }
        echo $controller->input;

    }

}