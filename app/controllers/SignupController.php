<?php

use Phalcon\Mvc\Controller;

class SignupController extends BaseController
{
    public function indexAction()
    {
    }

    public function registerAction()
    {
        $user = new Users();

        $user->assign(
            $this->request->getPost(),
            [
                'name',
                'email'
            ]
        );

        if($this->doesUserNameAlreadyExist(($user)) || $this->doesUserEmailAlreadyExist($user))
        {
            $message = "User already exists.";
            $success = false;
        }
        else
        {
            $success = $user->save();

            if ($success)
            {
                $message = "Thanks for registering!";
                $this->session->set(IS_LOGGED_IN, true);
            }
            else
            {
                $message = "Sorry, encountered the following issues:<br>"
                    . implode('<br>', $user->getMessages());
            }
        }

        $this->view->success = $success;
        $this->view->message = $message;
    }

    public function doesUserNameAlreadyExist($user)
    {
        $alreadyExists = Users::findFirst(
            [
                'conditions'  => 'name = :name:',
                'bind'        => [
                    'name' => $user->name
                ],
            ]
        );

        $alreadyExists = !is_null($alreadyExists);

        return $alreadyExists;
    }

    public function doesUserEmailAlreadyExist($user)
    {
        $alreadyExists = Users::findFirst(
            [
                'conditions'  => 'email = :email:',
                'bind'        => [
                    'email' => $user->email
                ],
            ]
        );

        $alreadyExists = !is_null($alreadyExists);

        return $alreadyExists;
    }
}
