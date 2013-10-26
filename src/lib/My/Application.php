<?php

/**
 * Simple application wrapper for ZF1
 */
class My_Application
{
    const ENV_PROD = 'production';
    const ENV_DEV = 'development';

    /**
     * Front controller is a singleton, application instances should not overlap
     *
     * @var bool
     */
    protected static $isRunning = false;

    /**
     * @var My_ServiceManager
     */
    protected $serviceManager;

    /**
     * @var Zend_Config
     */
    protected $config;

    /**
     * Create new application; ensure backward compatybiliy with zend framework concepts
     * Re-register zend registry to force it to use our container
     * Inject configuration into service manager (the container)
     *
     * @param Zend_Config $config
     * @param My_ServiceManager $sm
     * @param string $environment
     * @throws RuntimeException
     */
    public function __construct(Zend_Config $config, My_ServiceManager $sm, $environment = self::ENV_PROD)
    {
        if(self::$isRunning){
            throw new \RuntimeException('Application was already started - you can start only one instance at a time');
        }
        $this
            ->setServiceManager($sm)
            ->setConfig($config);

        // inject configuration into service manager
        $this->getServiceManager()->setConfig($config);
        $this->backwardCompatybilitySetup();
    }

    /**
     * Compatibility with ZF1 concepts
     * replace Zend_Registry with our proxy-container containing DI container
     * this should be started as early as possible
     */
    protected function backwardCompatybilitySetup()
    {
        Zend_Registry::setInstance(new My_Registry($this->getServiceManager()));
        /** @var $renderer Zend_Controller_Action_Helper_ViewRenderer */
        $renderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $renderer->setView($this->getServiceManager()->getView());
    }

    /**
     * @param \Zend_Config $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return \Zend_Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \My_ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return \My_ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Handle request and return a response object
     *
     * @param Zend_Controller_Request_Http $request
     * @param Zend_Controller_Response_Http $response
     * @return Zend_Controller_Response_Http
     */
    public function handle(Zend_Controller_Request_Http $request = null, Zend_Controller_Response_Http $response = null)
    {
        if(!$request){
            $request = $this->getServiceManager()->getRequest();
        }
        $response = $this->getServiceManager()->getFrontController()->dispatch($request, $response);
        return $response;
    }
}
