<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\Message\AuthnResponse;


/**
 * The response sent by the IdP after sending the authn request.
 *
 */
class IdpAuthnResponse extends AbstractResponse implements AuthnResponseInterface
{


    /**
     * {@inhertidoc}
     * @see \Saml\Ecp\Response\AuthnResponseInterface::getConsumerEndpointUrl()
     */
    public function getConsumerEndpointUrl()
    {
        $url = $this->getSoapMessage()
            ->getAssertionConsumerServiceUrl();
        if (! $url) {
            throw new Exception\InvalidResponseException('Missing AssertionConsumerServiceUrl');
        }
        
        return $url;
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\AbstractResponse::_createSoapMessage()
     */
    protected function _createSoapMessage($content)
    {
        return new AuthnResponse($content);
    }
}