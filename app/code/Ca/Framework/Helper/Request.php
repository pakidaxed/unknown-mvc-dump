<?php

namespace Ca\Framework\Helper;

class Request
{
    private $post;

    private $get;

    private $server;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->server = $_SERVER;
    }

    public function getPost($key = null)
    {

        if ($key !== null) {
            return isset($this->post[$key]) ? $this->post[$key] : null;
        } else {
            return $this->post;
        }
    }

    public function getRoute()
    {
        $url = [];
        if (!isset($this->server['PATH_INFO'])) {
            $url['controller'] = '/';
            return $url;
        }

        $path = $this->server['PATH_INFO'];
        $path = trim($path, '/');
        // controller/method/param
        $path = explode('/', $path);
        // [ 0=> controller, 1=>method, 2=>param]
        $url['controller'] = $path[0];

        if (isset($path[1])) {
            $url['method'] = $path[1];
        }
        if (isset($path[2])) {
            $url['param'] = $path[2];
        }
        return $url;
    }


}


