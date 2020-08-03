<?php

use Phalcon\Mvc\Controller;
use Phalcon\Assets\Asset\Js;

class IndexController extends BaseController
{
    public function indexAction()
    {
        parent::initialize();

        $customJS = new Js(
            '/js/views/index/custom.js',
            true,
            false,
            [],
            VERSION,
            false
        );

        $this->assets->addAssetByType('js', $customJS);

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

                $thumbnailContents = file_get_contents(BASE_PATH . $pathVideoThumbnail);
                $thumbnailBase64Data = base64_encode($thumbnailContents);

                //TODO: Get MIME type.
                $this->view->videosList .= "<img src=\"data:image/png;base64, $thumbnailBase64Data\"
                         alt=\"$videoFileName\"
                         class=\"img-thumbnail\"
                         id=\"$videoID\"
                         onclick=\"loadSelectedVideo(this, '" . FQDN . "');\">";
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
