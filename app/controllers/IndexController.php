<?php

use Phalcon\Mvc\Controller;

class IndexController extends BaseController
{
    public function indexAction()
    {
        if($this->session->get(IS_LOGGED_IN))
        {
            $user = $this->session->get(USER);
            $userID = $user->id;
            $userName = $user->name;

            $userVideos = $this->getUserVideos($userID);
            $this->session->set(USER_VIDEOS, $userVideos);

            foreach ($userVideos as $userVideo)
            {
                $videoID = $userVideo->id;
                $videoFileName = $userVideo->name;
                $videoThumbnail = $userVideo->thumbnail;

                $pathVideoThumbnail = VIDEO_UPLOAD_DIRECTORY . $userID . "_" . $userName . THUMBNAILS_DIRECTORY .
                    $videoThumbnail;

                $thumbnailBase64Data = base64_encode(file_get_contents(BASE_PATH . $pathVideoThumbnail));

                $this->view->videosList .= "<img src=\"data:image/png;base64, $thumbnailBase64Data\"
                         alt=\"$videoFileName\"
                         class=\"img-thumbnail\"
                         id=\"$videoID\">";
            }
        }
    }

    private function getUserVideos($userID)
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
