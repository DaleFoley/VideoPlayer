<?php

use Phalcon\Mvc\Controller;
use Phalcon\Assets\Asset\Js;

class BaseController extends Controller
{
    public function initialize()
    {
        $customJS = new Js(
            '/js/functions.js',
            true,
            false,
            [],
            VERSION,
            false
        );

        $this->assets->addAssetByType('js', $customJS);

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