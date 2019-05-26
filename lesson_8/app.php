<?php
    require_once 'engine/Logger.class.php';
    register_shutdown_function('ErrorShutdownHandler'); // Обработчик фатальных ошибок
try{
    require_once '../autoload.php';
    App::init();
}
catch (PDOException $e){
    echo "DB is not available";
    Logger::write('не удалось подключится к БД', $e, false);
}
catch (Exception $e){
    Logger::write($e->getMessage(), 'App false', false);
}



/**
 * Обработчик фатальных ошибок.  Если они есть, отправляем клиента на 404 страницу.
 * При этом в лог пишем саму ошибку.
 */
function ErrorShutdownHandler(){
    if(!is_null($e = error_get_last())){
        $code = isset($e['type']) ? $e['type'] : 0;
        if($code > 0) new FatalError($e, $code);
    }
}