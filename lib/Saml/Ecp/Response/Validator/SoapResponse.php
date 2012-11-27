<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


class SoapResponse extends AbstractValidator
{

    protected $_defContentType = 'application/soap+xml';


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid (ResponseInterface $response)
    {
        $soapMessage = $response->getSoapMessage();
        if ($soapMessage->isFault()) {
            $this->addMessage(sprintf("Fault SOAP response: [%s] %s (%s)", $soapMessage->getFaultCode(), $soapMessage->getFaultString(), $soapMessage->getFaultDetail()));
            return false;
        }
        
        return true;
    }
}