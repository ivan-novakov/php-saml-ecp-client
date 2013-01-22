<?php

namespace Saml\Ecp\Soap\Message;


/**
 * Authn response SOAP message.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class AuthnResponse extends Message
{


    /**
     * Returns the endpoint URL, where the client must deliver the SAML AuthnResponse message.
     * 
     * XPath: /S:Envelope/S:Header/ecp:Response/@AssertionConsumerServiceURL
     * 
     * @return string
     */
    public function getAssertionConsumerServiceUrl ()
    {
        return $this->getNodeValueByXpath('/S:Envelope/S:Header/ecp:Response/@AssertionConsumerServiceURL');
    }
}