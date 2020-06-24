<?php

use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    public function indexAction()
    {
        $post = $this->request->getPost();
        if(!empty($post))
        {
            $foundUser = $this->getUser($post['name'], $post['email']);

            $message = "Login Success!";
            if(is_null($foundUser))
            {
                $message = "Invalid Credentials";
                $this->view->invalid = true;
            }
            else
            {
                $this->session->set(IS_LOGGED_IN, true);
            }

            $this->view->message = $message;
        }
    }

    public function getUser($username, $email)
    {
        $user = Users::findFirst(
            [
                'conditions'  => 'name = :name: AND email = :email:',
                'bind'        => [
                    'name' => $username,
                    'email' => $email
                ],
            ]
        );

        return $user;
    }
}