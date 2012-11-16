<?php

namespace Saml\Ecp\Soap\Message;


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