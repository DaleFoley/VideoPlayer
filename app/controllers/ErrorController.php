<?php

use Phalcon\Mvc\Controller;

class ErrorController extends Controller
{
    public function indexAction()
    {
        $this->view->genericErrorMessage = "Encountered an Error. If this continues please report this to the web admin.\n\n";
        $this->view->genericErrorMessage .= "webadmin@localhost.com";
    }
}
