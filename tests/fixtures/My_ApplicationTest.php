<?php

/**
 * Class My_ApplicationTest
 */
class My_ApplicationTest extends PHPUnit_Framework_TestCase {
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

    public function testRequestReturnsValidResponse()
    {
        $this->request->setRequestUri('/index');
        $response = $this->app->handle($this->request);

        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertContains('ZF1 Boilerplate App', $response->getBody());
    }

    public function testRequestReturnsHttpError()
    {
        $this->request->setRequestUri('/index/this-address-is-invalid');
        $response = $this->app->handle($this->request);

        $this->assertEquals(500, $response->getHttpResponseCode());
        $this->assertContains('Ooops ! We have a problem !', $response->getBody());
    }

    public function testRequestReturnsValidResponseOnAdminModule()
    {
        $this->request->setRequestUri('/admin');
        $response = $this->app->handle($this->request);

        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertContains('Admin !', $response->getBody());
    }

    public function testDummyServiceProviderRegistration()
    {
        $this->app->register(new My_ServiceProvider_DummyServiceProvider());
        $result = $this->app->getServiceManager()->dummyService();

        $this->assertEquals('dummy service result', $result);

    }
}
