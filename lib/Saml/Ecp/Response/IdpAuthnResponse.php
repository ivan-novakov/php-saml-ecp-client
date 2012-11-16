<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\Message\AuthnResponse;


/**
 * The response sent by the IdP after sending the authn request.
 *
 */
class IdpAuthnResponse extends AbstractResponse
{


    /**
     * Returns the SP endpoint to which the response needs to be delivered.
     * 
     * @throws Exception\InvalidResponseException
     * @return string
     */
    public function getConsumerEndpointUrl ()
    {
        $url = $this->getSoapMessage()
            ->getAssertionConsumerServiceUrl();
        if (! $url) {
            throw new Exception\InvalidResponseException('Missing AssertionConsumerServiceUrl');
        }
        
        return $url;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\AbstractResponse::_createSoapMessage()
     */
    protected function _createSoapMessage ($content)
    {
        return new AuthnResponse($content);
    }
}