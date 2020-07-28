<?php

use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\LoggerFactory;

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function createFileOrDirectoryIfNotExists($pathToFileOrDirectory, $mode = 0777, $recursive = true)
{
    $isFileOrDirectoryCreated = false;
    if(!file_exists($pathToFileOrDirectory))
    {
        $lengthOfPathToFileOrDirectory = count($pathToFileOrDirectory);
        $isFileOrDirectoryCreated = mkdir($pathToFileOrDirectory, $mode, $recursive);
    }

    return $isFileOrDirectoryCreated;
}

function extractFrameFromVideo($pathSourceVideo, $pathToExtractedImage, $frame, $dirLogs = "")
{
    //TODO: Check path exists, create if doesn't exist function.
    $isDirLogsEmpty = empty($dirLogs);

    if(PHP_OS === "Linux")
    {
        if(!$isDirLogsEmpty)
        {
            $pathToErrorLogFile = PATH_FFMPEG_LINUX_BASE . LOGS_DIRECTORY . $dirLogs . '\\' . FFMPEG_ERROR_LOG;
            $pathToDebugLogFile = PATH_FFMPEG_LINUX_BASE . LOGS_DIRECTORY . $dirLogs . '\\' . FFMPEG_DEBUG_LOG;
        }
        else
        {
            $pathToErrorLogFile = PATH_FFMPEG_LINUX_ERROR_LOG;
            $pathToDebugLogFile = PATH_FFMPEG_LINUX_DEBUG_LOG;
        }

        $ffmpegCommand = PATH_FFMPEG_WINDOWS . " -i " . $pathSourceVideo . " -vf \"select=eq(n\,$frame)\" -vframes 1 " .
            $pathToExtractedImage . " </dev/null >> " . $pathToDebugLogFile . " 2>> " .
            $pathToErrorLogFile . " &";
    }
    else
    {
        if(!$isDirLogsEmpty)
        {
            $pathToErrorLogFile = PATH_FFMPEG_WINDOWS_BASE . LOGS_DIRECTORY . $dirLogs . '\\' . FFMPEG_ERROR_LOG;
            $pathToDebugLogFile = PATH_FFMPEG_WINDOWS_BASE . LOGS_DIRECTORY . $dirLogs . '\\' . FFMPEG_DEBUG_LOG;
        }
        else
        {
            $pathToErrorLogFile = PATH_FFMPEG_WINDOWS_ERROR_LOG;
            $pathToDebugLogFile = PATH_FFMPEG_WINDOWS_DEBUG_LOG;
        }

        $ffmpegCommand = "START /B CMD /C CALL " . PATH_FFMPEG_WINDOWS . " -i " . $pathSourceVideo .
            " -vf \"select=eq(n\,$frame)\" -vframes 1 " . $pathToExtractedImage .
            " >> " . $pathToDebugLogFile . " 2> " . $pathToErrorLogFile;
    }

    shell_exec($ffmpegCommand);
}

function outputLoggedOutNavBar()
{
    echo '<nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand fas fa-video" href="/">
                Video Player
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/signup">Register</a>
                    </li>
                </ul>
            </div>
        </nav>';
}

function outputLoggedInNavBar($container)
{
    $username = "";
    $user = $container->session->get(USER);

    if($user !== null)
    {
        $username = "Logged In User: " . $user->name;
    }

    echo '<nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand fas fa-video" href="/">
                Video Player ' . $username . '
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>';
}

function outputNavBar($container)
{
    $isLoggedIn = $container->session->get(IS_LOGGED_IN);
    if($isLoggedIn)
    {
        outputLoggedInNavBar($container);
    }
    else
    {
        outputLoggedOutNavBar();
    }
}