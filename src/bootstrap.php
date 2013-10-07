<?php

/**
 * Startup script for Zend For Facebook
 */

try {

    Zend_Controller_Front::getInstance()
        //->setParam('container', MyDiContainer)
        ->throwExceptions(false)
        ->setControllerDirectory(APPLICATION_PATH . '/controllers')
        ->dispatch();

}catch (Exception $e){
    if(APPLICATION_ENV == 'development'){
        printf('<h1>Blad: %s</h1><p class="lead">%s</p>', get_class($e), $e->getMessage());
    }else{
        printf('Wystąpił błąd - pracujemy nad jego rozwiązaniem !');
    }
}