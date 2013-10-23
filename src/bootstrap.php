<?php

/**
 * Bootstrap script for ZF1 boilerplate
 * @author Jacek Kobus
 */

try {

    $app = new My_Application(
        new Zend_Config(include_once(__DIR__ . '/config.php')),
        new My_ServiceManager(), APPLICATION_ENV
    );

    $app->handle()->sendResponse();

}catch (Exception $e){
    if(APPLICATION_ENV == 'development'){
        printf('<h1>Oooops ! %s</h1><p class="lead">%s</p>', get_class($e), $e->getMessage());
    }else{
        printf('Something is broken and we are fixing it right now !');
    }
}