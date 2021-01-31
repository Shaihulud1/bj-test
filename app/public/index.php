<?php 
require 'autoload.php';

function dd($var) {
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

$base = __DIR__.'/../src/';

$routes = require '../src/routes.php';
 
use core\Routing\Router;

$router = new Router;

foreach($routes as $r) {
    $router->addRouter($r);
}
$currentRoute = $router->getCurrentRoute();
if (!$currentRoute) {
    echo "Page not found";
    die;
}

session_start();

$controller = ucfirst($currentRoute['controller']);
$action = $currentRoute['action'];

if (!file_exists($base."controller/$controller.php")) {
    echo "Controller $controller not exist";
}
$controller = "controller\\".$controller;
$controllerObj = new $controller;

if (!method_exists($controllerObj, $action)) {
    echo "Action $action not exist";
}

$controllerObj->$action();







