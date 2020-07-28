<?php

use Phalcon\Mvc\Controller;
use Phalcon\Assets\Asset\Js;

class VideosController extends BaseController
{
    const BUFFER_BYTES = 1024;

    public function initialize()
    {
        parent::initialize();

        $customJS = new Js(
            '/js/views/upload/custom.js',
            true,
            false,
            [],
            VERSION,
            false
        );

        $this->assets->addAssetByType('js', $customJS);
    }

    public function indexAction($videoID)
    {
        $userVideo = self::getUserVideo($videoID);
        $userVideoPath = $userVideo->path;
        $userVideoName = $userVideo->name;

        $videoPath = BASE_PATH . $userVideoPath . '\\' . $userVideoName;
        $video = null;

        $video = fopen($videoPath, 'rb');

        if(!$video === false)
        {
            ob_get_clean();

            $fileSize = filesize($videoPath);

            $end = $fileSize;

            $buffer = (int)($end / self::BUFFER_BYTES);
            $remainder = $end % self::BUFFER_BYTES;

            if($fileSize !== null)
            {
                header("Content-Type: video/mp4"); //TODO: Interrogate video MIME type, set accordingly.
                header("Accept-Ranges: 0-" . $end);
                header("Content-Length: " . $fileSize);

                $this->stream($buffer, $video, $remainder);
                $this->end($video);
            }
        }
    }

    public function uploadAction()
    {
        if ($this->request->hasFiles() == true)
        {
            $uploadedFiles = $this->request->getUploadedFiles();
            $videoFile = $uploadedFiles[0];
            $videoMIME = $videoFile->getType();

            if($videoMIME === MP4_MIME)
            {
                $userID = $this->session->get(USER)->id;
                $userName = $this->session->get(USER)->name;

                $directoryUserVideo = $userID . '_' . $userName;

                $pathUserVideos = VIDEO_UPLOAD_DIRECTORY . $directoryUserVideo;
                $pathFileSystemUserVideos = BASE_PATH . $pathUserVideos;
                if(!file_exists($pathFileSystemUserVideos))
                {
                    mkdir($pathFileSystemUserVideos);
                }

                $uploadedFileName = $videoFile->getName();
                $isUploadedFileMoved = $videoFile->moveTo($pathFileSystemUserVideos . '\\' . $uploadedFileName);

                if(!$isUploadedFileMoved)
                {
                    //TODO: File failed to upload
                    $break = true;
                }

                $directoryUserThumbnails = $pathFileSystemUserVideos . THUMBNAILS_DIRECTORY;
                if(!file_exists($directoryUserThumbnails))
                {
                    mkdir($directoryUserThumbnails);
                }

                $thumbnailImageName = str_replace(".mp4", ".png", $uploadedFileName);
                $pathVideoThumbnail = $directoryUserThumbnails . $thumbnailImageName;

                if(!file_exists($pathVideoThumbnail))
                {
                    $pathSourceVideo = $pathFileSystemUserVideos . '\\' . $uploadedFileName;
                    $logDirectory = $userID . '_' . $userName;

                    extractFrameFromVideo($pathSourceVideo, $pathVideoThumbnail, 1, $logDirectory);
                }

                $videoFileSize = $videoFile->getSize();
                $saveResult = $this->insertUserVideoRow($userID,
                    $uploadedFileName,
                    $pathUserVideos,
                    $videoFileSize,
                    $thumbnailImageName);

                if($saveResult === false)
                {
                    //TODO: Error handling.
                    $break = true;
                }
            }
        }
    }

    private function insertUserVideoRow($userID, $videoName, $path, $fileSize, $thumbnail)
    {
        $userVideoToBeInserted = new UserVideos();
        $userVideoToBeInserted->user_id = $userID;
        $userVideoToBeInserted->name = $videoName;
        $userVideoToBeInserted->path = $path;
        $userVideoToBeInserted->size = $fileSize;
        $userVideoToBeInserted->thumbnail = $thumbnail;
        $userVideoToBeInserted->length = "0:00"; //TODO: Get video length

        $saveResult = $userVideoToBeInserted->save();

        return $saveResult;
    }

    private function getUserVideo($videoID)
    {
        $user = $this->session->get(USER);
        $userID = $user->id;

        $userVideo = UserVideos::findFirst(
            [
                'conditions'  => 'id = :id: AND user_id = :user_id:',
                'bind'        => [
                    'id' => $videoID,
                    'user_id' => $userID
                ],
            ]
        );

        return $userVideo;
    }

    private function stream($buffer, $video, $remainder)
    {
        set_time_limit(0);

        for($i = 0; $i != self::BUFFER_BYTES; ++$i)
        {
            $data = fread($video, $buffer);
            echo $data;
            flush();
        }

        //Output remainder bytes
        $data = fread($video, $remainder);
        echo $data;
        flush();
    }

    private function end($video)
    {
        if($video !== null)
        {
            fclose($video);
        }
        exit();
    }
}
