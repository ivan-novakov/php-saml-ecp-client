<?php

namespace Saml\Ecp\Discovery\Method;


interface MethodInterface
{


    /**
     * Returns the IdP ECP endpoint.
     * 
     * @return string
     */
    public function getIdpEcpEndpoint ();
}