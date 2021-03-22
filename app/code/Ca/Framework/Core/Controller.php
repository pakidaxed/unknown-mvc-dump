<?php

namespace Ca\Framework\Core;

class Controller
{
    private $moduleName;

    public function __construct($module)
    {
        $this->moduleName = $module;
    }

    public function render($template, $data)
    {
        include_once PROJECT_ROOT_DIR . 'app/code/Ca/Framework/view/header.php';
        include_once PROJECT_ROOT_DIR . 'app/code/' . $this->moduleName . '/view/' . $template . '.php';
        include_once PROJECT_ROOT_DIR . 'app/code/Ca/Framework/view/footer.php';
    }
}