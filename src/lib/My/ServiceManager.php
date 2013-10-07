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
 * @method My_View getView()
 * @method Facebook getFacebook()
 * @method Zend_Config getFacebookConfig()
 */
class My_ServiceManager extends \Skajdo\Container\Container
{
    public function __construct(){
        parent::__construct();

        $this['view'] = $this->share(function(My_ServiceManager $sm){
            $sc = new My_View(); // do magic with your view if you want
            return $sc;
        });

        $this['facebook_config'] = $this->share(function(My_ServiceManager $sm){
            return $sm->getConfig()->get('facebook');
        });

        $this['facebook'] = $this->share(function(My_ServiceManager $sm){
            return new Facebook($sm->getFacebookConfig()->toArray());
        });

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