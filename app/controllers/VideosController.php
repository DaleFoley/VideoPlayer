<?php

use Phalcon\Mvc\Controller;

class VideosController extends Controller
{
    //TODO: Async video streaming
    public function indexAction()
    {
        //TODO: Use routing to get video by ID.
        $videoPath = BASE_PATH . "/uploaded_videos/epic_wallbang_headshot.mp4";
        $video = null;

        //Uncomment to test
        $video = fopen($videoPath, 'rb');

        if(!$video === false)
        {
            ob_get_clean();

            $fileSize = filesize($videoPath);

            $end = $fileSize - 1;

            //TODO: Set 1024 as a constant.
            $buffer = (int)($end / 1024);
            $remainder = $end % 1024;

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
            //TODO: Process uploaded video, ensure mp4 videos are only uploaded for now.
            $uploadedFiles = $this->request->getUploadedFiles();
            $videoFile = $uploadedFiles[0];
            $videoMIME = $videoFile->getType();

            if($videoMIME === MP4_MIME)
            {
                $userID = $this->session->get(USER)->id;
                $userName = $this->session->get(USER)->name;

                $pathUserVideos = PATH_VIDEO_UPLOAD . '/' . $userID . '_' . $userName;
                if(!file_exists($pathUserVideos))
                {
                    mkdir($pathUserVideos);
                }
            }

            $break = true;
        }
    }

    private function stream($buffer, $video, $remainder)
    {
        set_time_limit(0);

        for($i = 0; $i != 1024; ++$i)
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
        exit;
    }
}
