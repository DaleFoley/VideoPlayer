<?php

use Phalcon\Mvc\Controller;

class ErrorController extends Controller
{
    public function indexAction()
    {
        exit();
    }
}
