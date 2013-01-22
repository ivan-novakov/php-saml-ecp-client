<?php

namespace Saml\Ecp\Flow;

use Saml\Ecp\Client\Client;
use Saml\Ecp\Authentication;
use Saml\Ecp\Discovery;


/**
 * Flow interface - describes the API for objects implementing the whole authentication ECP flow.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface FlowInterface
{


    /**
     * Sets the ECP client.
     *
     * @param Client $client
     */
    public function setClient(Client $client);


    /**
     * Performs a full authentication flow.
     * 
     * @param string $protectedContentUrl
     * @param Authentication\Method\MethodInterface $authenticationMethod
     * @param Discovery\Method\MethodInterface $discoveryMethod
     * @return ResponseInterface
     */
    public function authenticate($protectedContentUrl, Discovery\Method\MethodInterface $discoveryMethod, 
        Authentication\Method\MethodInterface $authenticationMethod);
}