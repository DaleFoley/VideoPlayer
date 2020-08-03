<?php
DEFINE('VERSION', '1.1');

if(strtolower(ENVIRONMENT) == 'production')
{
    DEFINE('PROTOCOL', 'HTTPS://');
}
else
{
    DEFINE('PROTOCOL', 'HTTP://');
}

DEFINE('FQDN', PROTOCOL . $_SERVER['HTTP_HOST']);

//Session variables
DEFINE('IS_LOGGED_IN', 'isLoggedIn');
DEFINE('USER', 'user');
DEFINE('USER_VIDEOS', 'userVideos');

//MIME types
DEFINE('VIDEO_MIME', 'video/');
DEFINE('MP4_MIME', VIDEO_MIME . 'mp4');
DEFINE('MPEG_MIME', VIDEO_MIME . 'mpeg');
DEFINE('OGV_MIME', VIDEO_MIME . 'ogg');
DEFINE('WEBM_MIME', VIDEO_MIME . 'webm');
DEFINE('AVI_MIME', VIDEO_MIME . 'x-msvideo');

//Directories
DEFINE('FFMPEG_WINDOWS_DIRECTORY', '\ffmpeg\win32\\');
DEFINE('FFMPEG_LINUX_DIRECTORY', '\ffmpeg\linux\\');
DEFINE('VIDEO_UPLOAD_DIRECTORY', '\uploaded_videos\\');
DEFINE('THUMBNAILS_DIRECTORY', '\thumbnails\\');
DEFINE('LOGS_DIRECTORY', 'logs\\');

//File names
DEFINE('FFMPEG_DEBUG_LOG', 'ffmpeg-debug.log');
DEFINE('FFMPEG_ERROR_LOG', 'ffmpeg-error.log');

//Paths
DEFINE('PATH_FFMPEG_WINDOWS_BASE', APP_PATH . FFMPEG_WINDOWS_DIRECTORY);
DEFINE('PATH_FFMPEG_LINUX_BASE', APP_PATH . FFMPEG_LINUX_DIRECTORY);
DEFINE('PATH_FFMPEG_WINDOWS', PATH_FFMPEG_WINDOWS_BASE . "ffmpeg.exe");
DEFINE('PATH_FFPLAY_WINDOWS', PATH_FFMPEG_WINDOWS_BASE . "ffplay.exe");
DEFINE('PATH_FFPROBE_WINDOWS', PATH_FFMPEG_WINDOWS_BASE . "ffprobe.exe");
DEFINE('PATH_FFMPEG_WINDOWS_DEBUG_LOG',  PATH_FFMPEG_WINDOWS_BASE . LOGS_DIRECTORY . FFMPEG_DEBUG_LOG);
DEFINE('PATH_FFMPEG_WINDOWS_ERROR_LOG', PATH_FFMPEG_WINDOWS_BASE . LOGS_DIRECTORY . FFMPEG_ERROR_LOG);
DEFINE('PATH_FFMPEG_LINUX', PATH_FFMPEG_LINUX_BASE . "ffmpeg.exe");
DEFINE('PATH_FFPLAY_LINUX', PATH_FFMPEG_LINUX_BASE . "ffplay.exe");
DEFINE('PATH_FFPROBE_LINUX', PATH_FFMPEG_LINUX_BASE . "ffprobe.exe");
DEFINE('PATH_FFMPEG_LINUX_DEBUG_LOG', PATH_FFMPEG_LINUX_BASE . LOGS_DIRECTORY . FFMPEG_DEBUG_LOG);
DEFINE('PATH_FFMPEG_LINUX_ERROR_LOG', PATH_FFMPEG_LINUX_BASE . LOGS_DIRECTORY . FFMPEG_ERROR_LOG);