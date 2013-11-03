<?php

/**
 * Dummy service provider - an example
 */
class My_ServiceProvider_DummyServiceProvider implements My_ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(My_Application $application)
    {
        $application->getServiceManager()->set('dummy_service', function(){
            return 'dummy service result';
}       );
    }
}