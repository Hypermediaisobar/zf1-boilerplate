<?php

/**
 * Simple application wrapper for ZF1
 * Inspired by Symfony2, Silex and Zf2 :)
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
     * @var My_Registry
     */
    protected $internalRegistry;

    /**
     * @var Zend_Config
     */
    protected $config;

    /**
     * The application path
     *
     * @var string
     */
    protected $applicationPath;

    /**
     * The environment
     *
     * @var string
     */
    protected $environment;

    /**
     * Create new application; ensure backward compatybiliy with zend framework concepts
     * Re-register zend registry to force it to use our container
     * Inject configuration into service manager (the container)
     *
     * @param Zend_Config $config
     * @param My_ServiceManager $sm
     * @param string $applicationPath
     * @param string $environment
     * @throws RuntimeException
     */
    public function __construct(Zend_Config $config, My_ServiceManager $sm, $applicationPath = null, $environment = self::ENV_PROD)
    {
        if(self::$isRunning){
            throw new \RuntimeException('Application was already started - you can start only one instance at a time');
        }

        $this->setApplicationPath($applicationPath);
        $this->setConfig($config);
        $this->setEnvironment($environment);
        $this->setServiceManager($sm);

        // inject configuration into service manager
        $this->backwardCompatybilitySetup();
    }

    /**
     * Compatibility with ZF1 concepts
     * replace Zend_Registry with our proxy-container containing DI container
     * this should be started as early as possible
     */
    protected function backwardCompatybilitySetup()
    {
        $this->internalRegistry = $internalRegistry = new My_Registry($this->getServiceManager());

        try{
            Zend_Registry::setInstance($internalRegistry);
        }catch (Zend_Exception $e){
            if($e->getMessage() == 'Registry is already initialized'){

                // hack for private properties in Zend_Registry
                $reflectedRegistry = new ReflectionClass('Zend_Registry');
                $refProperty = $reflectedRegistry->getProperty('_registry');
                $refProperty->setAccessible(true);
                $refProperty->setValue(null, null);

                Zend_Registry::setInstance($internalRegistry);
            }else{
                throw $e;
            }
        }

        /** @var $renderer Zend_Controller_Action_Helper_ViewRenderer */
        $renderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $renderer->setView($this->getServiceManager()->getView());
    }

    /**
     * @param string $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Set application path
     *
     * @param string $applicationPath
     * @return $this
     */
    public function setApplicationPath($applicationPath)
    {
        $this->applicationPath = $applicationPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getApplicationPath()
    {
        return $this->applicationPath;
    }

    /**
     * Set config.
     * This also injects configuration into service manager.
     *
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
    public function setServiceManager(My_ServiceManager $serviceManager)
    {
        $serviceManager->setApplication($this);
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
     * For TEST purposes only !
     * Unfortunately ZF1 relays on static calls and singletons
     * There can be ONLY ONE instance of application at a time
     * This methods clears everything including front controller
     */
    public function reset()
    {
        $this->getServiceManager()->getFrontController()->resetInstance();
        $this->config = null;
        $this->internalRegistry->setContainer(new \Pimple());
    }

    /**
     * Register new service for our application
     *
     * @param My_ServiceProviderInterface $service
     * @return $this
     */
    public function register(My_ServiceProviderInterface $service)
    {
        $service->register($this);
        return $this;
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
