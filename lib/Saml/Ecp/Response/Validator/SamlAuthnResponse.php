<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Soap\Message\AuthnResponse;
use Saml\Ecp\Response\ResponseInterface;


class SamlAuthnResponse extends AbstractValidator
{


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid (ResponseInterface $response)
    {
        return $this->_isValidSoapMessage($response->getSoapMessage());
    }


    /**
     * Validates the SAML response message.
     * 
     * @param AuthnResponse $soapMessage
     * @return boolean
     */
    protected function _isValidSoapMessage (AuthnResponse $soapMessage)
    {
        $consumerUrl = $soapMessage->getAssertionConsumerServiceUrl();
        if (! $consumerUrl) {
            $this->addMessage('Missing AssertionConsumerServiceURL value');
            return false;
        }
        
        // compare with SP URL
        
        return true;
    }
}