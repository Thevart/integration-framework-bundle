<?php

namespace Smartbox\Integration\FrameworkBundle\Traits;


use Smartbox\Integration\FrameworkBundle\Endpoints\EndpointFactory;

trait UsesEndpointFactory {

    /** @var  EndpointFactory */
    protected $endpointFactory;

    /**
     * @return EndpointFactory
     */
    public function getEndpointFactory()
    {
        return $this->endpointFactory;
    }

    /**
     * @param EndpointFactory $endpointFactory
     */
    public function setEndpointFactory($endpointFactory)
    {
        $this->endpointFactory = $endpointFactory;
    }

}