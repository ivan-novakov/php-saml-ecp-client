<?php

namespace Saml\Ecp\Authentication\Method;

use Zend\Http\Client;


/**
 * Interface for authentication methods.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface MethodInterface
{


    /**
     * Configures the HTTP client to use the required authentication method.
     * 
     * @param Client $httpClient
     */
    public function configureHttpClient(Client $httpClient);
}