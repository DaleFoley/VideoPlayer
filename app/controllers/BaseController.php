<?php

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    public function initialize()
    {
        if(is_null($this->session->get(IS_LOGGED_IN)) &&
                    $_SERVER['REQUEST_URI'] !== "/" &&
                    $_SERVER['REQUEST_URI'] !== "/login" &&
                    $_SERVER['REQUEST_URI'] !== "/login/index")
        {
            header('Location: /');
            exit();
        }
    }
}