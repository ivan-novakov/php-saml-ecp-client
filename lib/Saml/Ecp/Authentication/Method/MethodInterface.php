<?php

namespace Saml\Ecp\Authentication\Method;

use Zend\Http\Client;


interface MethodInterface
{


    /**
     * Configures the HTTP client to use the required authentication method.
     * 
     * @param Client $httpClient
     */
    public function configureHttpClient (Client $httpClient);
}