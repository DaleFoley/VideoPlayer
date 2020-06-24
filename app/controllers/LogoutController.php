<?php

use Phalcon\Mvc\Controller;

class LogoutController extends Controller
{
    public function indexAction()
    {
        $this->session->remove('isLoggedIn');
        $this->view->message = "You have logged out.";
    }
}