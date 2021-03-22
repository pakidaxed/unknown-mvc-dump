<?php

namespace Ca\Framework\Helper;

class Router
{
    public const ROUTING_FILE = 'routing.php';
    public const DEFAULT_METHOD = 'index';

    public function loadController($url)
    {
        include_once CONFIG_DIR .'/'. self::ROUTING_FILE;
        $method = self::DEFAULT_METHOD;
        $param = null;

        /* https://127.0.0.1:8001/products/edit/1
         * $url = [controller => products, methos=>show, param=>1]
         */

        $keyFromRoutingFile = $url['controller'];
        if (isset($routes[$keyFromRoutingFile])) { // $routes['products']
            $controllerClass = $routes[$keyFromRoutingFile];
            isset($url['method']) ? $method = $url['method'] : $method = self::DEFAULT_METHOD;
            isset($url['param']) ? $param = $url['param'] : $param = null;
        } else {
            $controllerClass = $routes['error'];
        }

        $controller = new $controllerClass;
        if (method_exists($controller, $method)) {
            if ($param !== null) {
                $controller->$method($param);
            } else {
                $controller->$method();
            }
        } else {
            $controller = new $routes['error'];
            $controller->$method();
        }
    }
}