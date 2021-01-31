<?php
spl_autoload_register(function ($className) {
    $base = __DIR__.'/../src/';
    $fileName =  $base.$className.'.php';
    $fileName = str_replace("\\", "/", $fileName);
    if (file_exists($fileName)) {
        require $fileName;
    }
});