<?php

/**
 * Bootstrap script for Zend For Facebook
 * @author Jacek Kobus
 */

try {

    $config = new Zend_Config(include_once(__DIR__ . '/config.php'));
    $container = new My_ServiceManager();
    $container
        ->setConfig($config)
        ->setEnvironment(APPLICATION_ENV);

    // Compatibility with ZF1 concepts
    // replace Zend_Registry with our proxy-container containing DI container
    // this must be done BEFORE anything else !
    Zend_Registry::setInstance(new My_Registry($container));
    /** @var $renderer Zend_Controller_Action_Helper_ViewRenderer */
    $renderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
    $renderer->setView($container->getView());

    // modify the request object if you want to
    $request = $container->getRequest();
    $response = $container->getFrontController()->dispatch($request);
    $response->sendResponse();

}catch (Exception $e){
    if(APPLICATION_ENV == 'development'){
        printf('<h1>Oooops ! %s</h1><p class="lead">%s</p>', get_class($e), $e->getMessage());
    }else{
        printf('Something is broken and we are fixing it right now !');
    }
}