<?php
include_once '../vendor/autoload.php';
include_once '../config/env.php';

$request = new \Ca\Framework\Helper\Request;
$router = new \Ca\Framework\Helper\Router;

$url = $request->getRoute();
$router->loadController($url);