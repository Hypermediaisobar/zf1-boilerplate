<?php

/**
 * Handle all errors
 */
class ErrorController extends My_Controller
{
    /**
     * Handle error and display message
     *
     * @param string $action
     */
    public function dispatch($action)
    {
        if(!$this->hasParam('error_handler')){
            $this->forward('index', 'index');
        }

        /** @var $eh ArrayObject */
        $eh = $this->getParam('error_handler');
        $e = $eh['exception'];

        $this->getResponse()->setHttpResponseCode(500);

        $this->view->e = $e;
        $this->renderScript('error/index.phtml');
    }
}