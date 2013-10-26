<?php

/**
 * Container for our application
 * This container uses Lazy-loading design pattern along with DI pattern.
 * Names with underscores are magically translated to camelCase:
 * some_service becomes getSomeService()
 *
 * PHPStorm supports comments like:
 * @method getSomething($var) stdClass
 *
 * @method static Closure share(mixed $callable)
 * @method Zend_Controller_Front getFrontController()
 * @method Zend_Controller_Request_Http getRequest()
 * @method Zend_Controller_Router_Interface getRouter()
 * @method Zend_Controller_Dispatcher_Interface getDispatcher()
 * @method My_View getView()
 * @method Facebook getFacebook()
 * @method Zend_Log getLog()
 * @method My_Model getModel()
 */
class My_ServiceManager extends \Skajdo\Container\Container
{
    public function __construct(){
        parent::__construct();

        $this['model'] = $this->share(function(My_ServiceManager $sm){
            return new My_Model();
        });

        $this['request'] = $this->share(function(My_ServiceManager $sm){
            return new Zend_Controller_Request_Http();
        });

        $this['router'] = $this->share(function(My_ServiceManager $sm){
            return new Zend_Controller_Router_Rewrite();
        });

        $this['dispatcher'] = $this->share(function(My_ServiceManager $sm){
            return new Zend_Controller_Dispatcher_Standard();
        });

        $this['frontcontroller'] = /* an alias */
        $this['front_controller'] = $this->share(function(My_ServiceManager $sm){

                /** @var Zend_Config $options */
                $options = $sm->getConfig()->get('app');

                $fc = Zend_Controller_Front::getInstance();
                $fc
                    ->setRequest($sm->getRequest())
                    ->throwExceptions($options->get('throw-exceptions', false))
                    ->setBaseUrl($options->get('base-url', null))
                    ->setRouter($sm->getRouter())
                    ->setDispatcher($sm->getDispatcher())

                    ->setParam('service_manager', $sm)
                    ->setParam('displayExceptions', $options->get('display-exceptions'))
                    ->setDefaultModule($options->get('default-module'))
                    ->addModuleDirectory('./modules')
                    ->returnResponse(true)
                ;

                return $fc;
        });

        $this['view'] = $this->share(function(My_ServiceManager $sm){
            $view = new My_View();
            $view->setRequest($sm->getRequest())
                ->addScriptPath('./views')
                ->addScriptPath('./views/scripts')
                ->addScriptPath('./views/layouts')
            ;

            $layout = Zend_Layout::startMvc();
            $layout
                ->setView($view)
                ->setLayout('default')
            ;
            return $view;
        });

        $this['facebook'] = $this->share(function(My_ServiceManager $sm){
            /** @var Zend_Config $options */
            $options = $sm->getConfig()->get('facebook');
            $fbConfig = array(
                'appId' => $options->get('app-id'),
                'secret' => $options->get('secret'),
            );
            return new Facebook($fbConfig);
        });

        $this['log'] = $this->share(function(My_ServiceManager $sm){

            /** @var Zend_Config $options */
            $options = $sm->getConfig()->get('app');
            $writer = new Zend_Log_Writer_Stream(sprintf('%s/%s.log', $options->get('log-path'), date('Ymd')));
            $logFormat = '%timestamp% %priorityName% (%priority%): %message%' . PHP_EOL;
            $writer
                ->addFilter(new Zend_Log_Filter_Priority($options->get('log-priority')))
                ->setFormatter(new Zend_Log_Formatter_Simple($logFormat));

            $log = new Zend_Log($writer);
            $log->setTimestampFormat('d-m-Y H:i:s');

            return $log;
        });
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this['environment'];
    }

    /**
     * @param $env
     * @return $this
     */
    public function setEnvironment($env)
    {
        $this['environment'] = $env;
        return $this;
    }

    /**
     * Set application config
     *
     * @param Zend_Config $config
     * @return $this
     */
    public function setConfig(Zend_Config $config)
    {
        $this['config'] = $config;
        return $this;
    }

    /**
     * Application config
     *
     * @return Zend_Config
     */
    public function getConfig()
    {
        return $this['config'];
    }
}