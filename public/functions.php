<?php

use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\LoggerFactory;

require_once 'Mail.php';

function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function replaceStringTokens($stringToUpdate, $tokensToReplace, $tokenValues)
{
    $stringToUpdate = str_replace($tokensToReplace, $tokenValues, $stringToUpdate);
    return $stringToUpdate;
}

function sendHTMLEmail($from, $to, $subject, $tokens, $emailTemplate)
{
    $emailFileHandle = fopen($emailTemplate, 'r');
    $emailContent = fread($emailFileHandle, filesize($emailTemplate));

    $tokensToReplace = array_keys($tokens);
    $tokenValues = array_values($tokens);

    $emailContent = replaceStringTokens($emailContent, $tokensToReplace, $tokenValues);

    $recipients = $to;

    $headers['From'] = $from;
    $headers['To'] = $to;
    $headers['Subject'] = $subject;
    $headers['Content-Type'] = "text/html";

    $body = $emailContent;

    $params["host"] = MAIL_LOCALHOST;

    $params["port"] = MAIL_PORT;

    $params["auth"] = MAIL_AUTH;

    $params["username"] = MAIL_USERNAME;

    $params["password"] = MAIL_PASSWORD;

    $params["localhost"] = MAIL_LOCALHOST;

    $params["timeout"] = MAIL_TIMEOUT;

    $params["verp"] = MAIL_VERP;

    $params["debug"] = MAIL_DEBUG;

    $params["persist"] = MAIL_PERSIST;

    $params["pipelining"] = MAIL_PIPELINING;

    $mail_object = Mail::factory('smtp', $params);
    return $mail_object->send($recipients, $headers, $body);
}

function createFileOrDirectoryIfNotExists($pathToFileOrDirectory, $mode = 0777, $recursive = true)
{
    $isFileOrDirectoryCreated = false;
    if(!file_exists($pathToFileOrDirectory))
    {
        $pathInfo = pathinfo($pathToFileOrDirectory);
        $isFile = isset($pathInfo['extension']);

        if($isFile)
        {
            $createdFile = fopen($pathToFileOrDirectory, 'w');
            fclose($createdFile);

            $isFileOrDirectoryCreated = true;
        }
        else
        {
            $isFileOrDirectoryCreated = mkdir($pathToFileOrDirectory, $mode, $recursive);
        }
    }

    return $isFileOrDirectoryCreated;
}

function removeFileSuffix($fileName)
{
    return pathinfo($fileName, PATHINFO_FILENAME);
}

function extractFrameFromVideo($pathSourceVideo, $pathToExtractedImage, $frame, $dirLogs = "")
{
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

        createFileOrDirectoryIfNotExists($pathToErrorLogFile);
        createFileOrDirectoryIfNotExists($pathToDebugLogFile);

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

        createFileOrDirectoryIfNotExists($pathToErrorLogFile);
        createFileOrDirectoryIfNotExists($pathToDebugLogFile);

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