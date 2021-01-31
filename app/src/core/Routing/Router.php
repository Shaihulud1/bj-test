<?php
namespace core\Routing;

class Router
{
    private $routes = [];


    public function addRouter($route): void
    {
        if (!isset($route['action']) || !isset($route['controller']) || !isset($route['url'])) {
            echo "wrong route";
            dd($route);
            die;
        } 
        $this->routes[] = $route;
    }

    public function getCurrentRoute(): ?array
    {
        foreach ($this->routes as $route) 
        {
            $reqUri = trim($_SERVER['REQUEST_URI'], '/');
            if (preg_match('/^([a-zA-Z-_\/]+)\?/', $reqUri, $matches)) {
                $reqUri = $matches[1];
            }
            $pregrex = "#".$reqUri."#";
            if (preg_match($pregrex, $route['url'], $matches)) {
                return $route;
            }
        }
        return null;
    }
}