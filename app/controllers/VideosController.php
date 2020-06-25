<?php

use Phalcon\Mvc\Controller;

class VideosController extends Controller
{
    //TODO: Async video streaming
    public function indexAction()
    {
        //TODO: Use routing to get video by ID.
        $videoPath = BASE_PATH . "/uploaded_videos/";
        $video = null;
        //$video = fopen(BASE_PATH . "/uploaded_videos/", 'rb');

        if(!$video === false)
        {
            ob_get_clean();

            $fileSize = filesize(BASE_PATH . "/uploaded_videos/");

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
            //TODO: Process uploaded video, ensure videos are only uploaded.
            $uploadedFiles = $this->request->getUploadedFiles();
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
        fclose($video->stream);
        exit;
    }
}
