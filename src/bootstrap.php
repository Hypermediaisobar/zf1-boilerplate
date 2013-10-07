<?php

/**
 * Startup script for Zend For Facebook
 */

try {

    $config = new Zend_Config(array(
        'facebook' => array(
            'appId' => 123,
            'secret' => 123,
        )
    ));

    $container = new My_ServiceManager();
    $container->setConfig($config);

    /** @var $renderer Zend_Controller_Action_Helper_ViewRenderer */
    $renderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
    $renderer->setView($container->getView());

    Zend_Controller_Front::getInstance()
        ->setParam('service_manager', $container)
        ->throwExceptions(false)
        ->setControllerDirectory(APPLICATION_PATH . '/controllers')
        ->dispatch();

}catch (Exception $e){
    if(APPLICATION_ENV == 'development'){
        printf('<h1>Oooops ! %s</h1><p class="lead">%s</p>', get_class($e), $e->getMessage());
    }else{
        printf('Something is broken and we are fixing it right now !');
    }
}