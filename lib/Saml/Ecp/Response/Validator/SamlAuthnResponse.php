<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Exception as GeneralException;
use Saml\Ecp\Soap\Message\AuthnResponse;
use Saml\Ecp\Response\ResponseInterface;


/**
 * Checks if the response contains valid authn response.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class SamlAuthnResponse extends AbstractValidator
{

    /**
     * Option index.
     */
    const OPT_SP_ASSERTION_CONSUMER_URL = 'sp_assertion_consumer_url';


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid(ResponseInterface $response)
    {
        return $this->_isValidSoapMessage($response->getSoapMessage());
    }


    /**
     * Validates the SAML response message.
     * 
     * @param AuthnResponse $soapMessage
     * @return boolean
     */
    protected function _isValidSoapMessage(AuthnResponse $soapMessage)
    {
        $expectedConsumerUrl = $this->getOption(self::OPT_SP_ASSERTION_CONSUMER_URL);
        if (! $expectedConsumerUrl) {
            throw new GeneralException\MissingOptionException(self::OPT_SP_ASSERTION_CONSUMER_URL);
        }
        
        $consumerUrl = $soapMessage->getAssertionConsumerServiceUrl();
        if (! $consumerUrl) {
            $this->addMessage('Missing AssertionConsumerServiceURL value in AuthnResponse');
            return false;
        }
        
        if ($consumerUrl != $expectedConsumerUrl) {
            $this->addMessage(sprintf("The assertion consumer URL contained in the AuthnResponse (%s) is different 
                from the one declared by the SP(%s)", $consumerUrl, $expectedConsumerUrl));
            return false;
        }
        
        return true;
    }
}