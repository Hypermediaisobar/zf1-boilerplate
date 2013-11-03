<?php

/**
 * KISS
 */

date_default_timezone_set('Europe/Warsaw');

define('APPLICATION_ENV_PROD', 'production');
define('APPLICATION_ENV_DEV', 'development');
define("APPLICATION_MICROTIME_START", microtime(true));
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// APPLICATION_PATH points to ./src
define('APPLICATION_PATH', realpath(__DIR__ . '/../'));

chdir(APPLICATION_PATH);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';


