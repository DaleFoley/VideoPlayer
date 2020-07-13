<?php
//Reference: https://docs.phalcon.io/4.0/en/tutorial-basic
use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream as LoggingStream;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Assets\Asset\Js;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

require_once BASE_PATH . '/config.inc';
require_once BASE_PATH . '/public/strings.php';
require_once BASE_PATH . '/public/functions.php';

//Set a catch all exception handler for any unknowns.
set_exception_handler('globalExceptionHandler');

$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);

$loader->register();

$container = new FactoryDefault();
$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');

        return $view;
    }
);
$container->set(
    'logger',
    function () {
        $adapter = new LoggingStream(BASE_PATH . "/debug.log");
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        return $logger;
    }
);
$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');

        return $url;
    }
);
$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => DB_HOST,
                'username' => DB_USER,
                'password' => DB_PASSWD,
                'dbname'   => DB_NAME,
            ]
        );
    }
);
$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => BASE_PATH . '/tmp',
            ]
        );

        $session->setAdapter($files)->start();

        return $session;
    }
);

$application = new Application($container);

$response = $application->handle(
    $_SERVER["REQUEST_URI"]
);

$response->send();
