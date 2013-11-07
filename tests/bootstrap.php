<?php

date_default_timezone_set('Europe/Warsaw');

if(!defined('APPLICATION_PATH')){
    define('APPLICATION_PATH', realpath(__DIR__ . '/../src'));
}

if(!defined('APPLICATION_ENV')){
    define('APPLICATION_ENV', 'development');
}