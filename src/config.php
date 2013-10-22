<?php

/**
 * Configuration file
 * All options MUST BE IN LOWERCASE with "-" as a word separator !!
 */

return array(

    'app' => array(
        'controllers' => './controllers',
        'base-url' => null, // autodetect
        'display-exceptions' => APPLICATION_ENV != 'production',
        'throw-exceptions' => false && APPLICATION_ENV == 'development',
        'log-path' => '../log',
        'log-priority' => APPLICATION_ENV == 'development' ? Zend_Log::DEBUG : Zend_Log::WARN,
    ),

    'facebook' => array(
        'app-id' => 123,
        'secret' => 123,
    )
);