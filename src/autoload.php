<?php

try {
    require_once 'vendor/Twig/Autoloader.php';
    Twig_Autoloader::register();
    
    spl_autoload_register("gbStandardAutoload");
    
}
catch (Exception $e){
    echo   json_encode($e->getMessage());
  }



  function gbStandardAutoload($className){
    $dirs = [
    'model/',
    'engine/',
    'model/Catalog/',
    'model/Administration/',
    'controller/',
];
    $found = false;
    foreach ($dirs as $dir) {
        $fileName = __DIR__.'/' . $dir . $className . '.class.php';
        if (is_file($fileName)) {
            require_once($fileName);
            $found = true;
            break;
        }
    }
    if (!$found) {
        $found = false;
    }
    return $found;
}