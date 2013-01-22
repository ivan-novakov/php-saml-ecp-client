<?php

namespace Saml\Ecp\Response;


/**
 * Authn response interface - adds some specific methods.
 *
 */
interface AuthnResponseInterface extends ResponseInterface
{


    /**
     * Returns the SP endpoint to which the response needs to be delivered.
     *
     * @throws Exception\InvalidResponseException
     * @return string
     */
    public function getConsumerEndpointUrl();
}