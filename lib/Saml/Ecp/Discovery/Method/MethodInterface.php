<?php

namespace Saml\Ecp\Discovery\Method;


/**
 * Discovery method interface.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface MethodInterface
{


    /**
     * Returns the IdP ECP endpoint.
     * 
     * @return string
     */
    public function getIdpEcpEndpoint();
}