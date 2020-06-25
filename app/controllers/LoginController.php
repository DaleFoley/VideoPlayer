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
                $this->session->set(USER, $foundUser);

                $userVideos = $this->getUserVideos($foundUser->id);
                $this->session->set(USER_VIDEOS, $userVideos);
            }

            $this->view->message = $message;
        }
    }

    public function getUser($username, $email)
    {
        $user = Users::findFirst(
            [
                'conditions'  => 'name = :name: AND email = :email:',
                'bind' => [
                    'name' => $username,
                    'email' => $email
                ],
            ]
        );

        return $user;
    }

    public function getUserVideos($userID)
    {
        $videos = UserVideos::find(
            [
                'conditions' => 'user_id = :user_id:',
                'bind' => [
                    'user_id' => $userID
                ],
            ]
        );
        return $videos;
    }
}