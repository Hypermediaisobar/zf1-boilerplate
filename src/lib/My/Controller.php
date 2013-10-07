<?php

/**
 * Custom controller implementation
 * It must implement Zend_Controller_Action_Interface
 *
 * @method Zend_Controller_Request_Http getRequest()
 * @method My_View getView()
 *
 * @see Zend_Controller_Action_Interface
 */
class My_Controller extends Zend_Controller_Action implements Zend_Controller_Action_Interface
{
    /**
     * @var My_ServiceManager
     */
    private $serviceManager;

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs
     */
    public function __construct(
        Zend_Controller_Request_Abstract $request,
        Zend_Controller_Response_Abstract $response,
        array $invokeArgs = array()
    ) {
        $this->serviceManager = $invokeArgs['service_manager'];
        parent::__construct($request, $response, $invokeArgs);
    }

    /**
     * @return \My_ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}