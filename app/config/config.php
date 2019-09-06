<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');
defined('CAL_EMBARAZO') || define('CAL_EMBARAZO', '1');
defined('CAL_SEXO_BEBE') || define('CAL_SEXO_BEBE', '2');
defined('CAL_OJOS_BEBE') || define('CAL_OJOS_BEBE', '3');
defined('CAL_PESO_BEBE') || define('CAL_PESO_BEBE', '4');
defined('CAL_PELO_BEBE') || define('CAL_PELO_BEBE', '5');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '',
        'dbname'      => 'calculadoras',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'logPath'        => BASE_PATH . '/tmp/logs/error.log',
        'vendorDir'      => BASE_PATH . '/vendor/',
        'formsDir'       => APP_PATH . '/formsValidations/',
        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        //'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ]
]);
