<?php

require_once(__DIR__ . '/../bootstrap.php');

/**
 * Test requests
 * New application is beeing created for each test
 * @todo implement dom crawler
 */
class ApplicationTest extends \Skajdo\TestSuite\Test\TestFixture
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

    public function testRequestReturnsValidResponse()
    {
        $this->request->setRequestUri('/index');
        $response = $this->app->handle($this->request);

        $this->assert()->isIdentical(200, $response->getHttpResponseCode());
        $this->assert()->contains('ZF1 Boilerplate App', $response->getBody());
    }

    public function testRequestReturnsHttpError()
    {
        $this->request->setRequestUri('/index/this-address-is-invalid');
        $response = $this->app->handle($this->request);

        $this->assert()->isIdentical(500, $response->getHttpResponseCode());
        $this->assert()->contains('Ooops ! We have a problem !', $response->getBody());
    }

    public function testRequestReturnsValidResponseOnAdminModule()
    {
        $this->request->setRequestUri('/admin');
        $response = $this->app->handle($this->request);

        $this->assert()->isIdentical(200, $response->getHttpResponseCode());
        $this->assert()->contains('Admin !', $response->getBody());
    }

    public function testDummyServiceProviderRegistration()
    {
        $this->app->register(new My_ServiceProvider_DummyServiceProvider());
        $result = $this->app->getServiceManager()->dummyService();
        $this->assert()->isIdentical('dummy service result', $result);
    }
}