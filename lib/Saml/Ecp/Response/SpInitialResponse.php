<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\Message\AuthnRequest;


/**
 * The response sent by the SP, after the initial request for protected resource.
 * 
 * The response must contain a SAML AuthnRequest SOAP message.
 */
class SpInitialResponse extends AbstractResponse
{


    /**
     * Returns the URL of the endpoint, where the response has to be delivered.
     * 
     * @return string
     */
    public function getResponseConsumerUrl ()
    {
        $consumerUrl = $this->getSoapMessage()
            ->getAssertionConsumerServiceUrl();
        if (! $consumerUrl) {
            throw new Exception\InvalidResponseException('Missing AssertionConsumerServiceUrl');
        }
        
        return $consumerUrl;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\AbstractResponse::_createSoapMessage()
     * @return AuthnRequest
     */
    protected function _createSoapMessage ($content)
    {
        return new AuthnRequest($content);
    }
}