<?php

/**
 * Brief description of the controller
 */
class IndexController extends My_Controller
{
    /**
     * Your main action
     * Put a comment before each action and briefly describe what it does !
     */
    public function indexAction()
    {
        $this->getServiceManager()->getLog()->log('This is a test !', Zend_Log::INFO);
        $this->view->facebook = $this->getServiceManager()->getFacebook();
    }
}