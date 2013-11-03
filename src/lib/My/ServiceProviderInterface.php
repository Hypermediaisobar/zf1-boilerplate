<?php

/**
 * Service provider
 */
interface My_ServiceProviderInterface
{
    /**
     * Register the service in given application
     *
     * @param My_Application $application
     * @return void
     */
    public function register(My_Application $application);
}