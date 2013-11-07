<?php

/**
 * Dummy service provider test
 */
class DummyServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var My_Application
     */
    protected $app;

    /**
     * @var Zend_Controller_Request_HttpTestCase
     */
    protected $request;

    /**
     * Prepare our application
     */
    public function setUp()
    {
        $this->request = new Zend_Controller_Request_HttpTestCase();
        $this->request->setBasePath('http://localhost/zf1-boilerplate/public');

        $this->app = new My_Application(
            new Zend_Config(include(APPLICATION_PATH . '/config.php')),
            new My_ServiceManager(), APPLICATION_PATH, APPLICATION_ENV
        );
    }

    /**
     * Reset application instance
     */
    public function tearDown()
    {
        $this->app->reset();
    }

    public function testDummyServiceProviderRegistration()
    {
        $this->app->register(new My_ServiceProvider_DummyServiceProvider());
        $result = $this->app->getServiceManager()->dummyService();

        $this->assertEquals('dummy service result', $result);

    }
}
