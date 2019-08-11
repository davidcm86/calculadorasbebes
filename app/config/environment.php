<?php
    define('ENVIRONMENT', isset($_SERVER['APPLICATION_ENV']) ? $_SERVER['APPLICATION_ENV'] : 'development');
    $variableEntorno = (ENVIRONMENT == 'development') ? 1 : -1; 
    switch (ENVIRONMENT) {
        case 'development':
            define('DOMINIO', 'https://www.calculadorasparabebes.loc');
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('log_errors', '2'); 
            $debug = new \Phalcon\Debug(); // modo debug 
            $debug->listen();
            break;
        case 'production':
            define('DOMINIO', 'https://www.calculadorasparabebes.com');
            ini_set('display_errors', 0);
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
            break;
        default:
            header('HTTP/1.1 503 Service Unavailable.', true, 503);
            echo 'The application environment is not set correctly.';
            exit(1); // EXIT_ERROR
    }
    // cogemos los errores y si estamos en local los pintamos en pantalla, en pro solo van al log
    set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {
        $logger = new \Phalcon\Logger\Adapter\File(BASE_PATH.'/tmp/logs/error.log');
        $msg = "[$errno] $errstr ; \n on line $errline in file $errfile \n";
        switch ($errno) {
            case E_USER_ERROR:
                $logger->critical("fatal error: ". $msg);
                break;
            case E_USER_WARNING:
                $logger->warning("warning error: ". $msg);
                break;
            case E_USER_NOTICE:
                $logger->notice("notice error: ". $msg);
                break;
            default:
                $logger->notice("notice error: ". $msg);
                break;
        }
    }, $variableEntorno); // en desarrollo mostramos error en pantalla, en pro no