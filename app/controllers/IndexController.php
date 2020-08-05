<?php

use Phalcon\Mvc\Controller;
use Phalcon\Assets\Asset\Js;
use Phalcon\Assets\Asset\Css;

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

        $customCSS = new Css(
            '/css/views/index/card.css',
            true,
            false,
            [],
            VERSION,
            false
        );

        $this->assets->addAssetByType('css', $customCSS);

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
                $videoFileName = removeFileSuffix($userVideo->name);
                $videoThumbnail = $userVideo->thumbnail;

                $pathVideoThumbnail = VIDEO_UPLOAD_DIRECTORY . $userID . "_" . $userName . THUMBNAILS_DIRECTORY .
                    $videoThumbnail;

                $thumbnailContents = file_get_contents(BASE_PATH . $pathVideoThumbnail);
                $thumbnailBase64Data = base64_encode($thumbnailContents);

                //TODO: Get MIME type, replace extension with nothing.
                $this->view->videosList .= "<li class=\"list-group-item\">
        <div class=\"card-row-custom\">
            <img src=\"data:image/png;base64, $thumbnailBase64Data\"
                 alt=\"$videoFileName\"
                 id=\"$videoID\"
                 onclick=\"loadSelectedVideo(this, '" . FQDN . "');\"
                 width=\"164\"
                 height=\"90\">
            <div class=\"card-body p-0\">
                <h5 class=\"card-title-custom p-0\">$videoFileName</h5>
                <h6 class=\"card-subtitle mb-2 text-muted\">2020-12-24 - View Count</h6>
                <small class=\"card-text\">The author of the video.</small><br>
            </div>
        </div>
    </li>";
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
