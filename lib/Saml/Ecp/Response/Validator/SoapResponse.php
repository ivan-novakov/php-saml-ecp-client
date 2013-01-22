<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


/**
 * Checks if the response is a valid SOAP response.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class SoapResponse extends AbstractValidator
{
    
    //protected $_defContentType = 'application/soap+xml';
    

    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid(ResponseInterface $response)
    {
        $soapMessage = $response->getSoapMessage();
        if ($soapMessage->isFault()) {
            $this->addMessage(sprintf("Fault SOAP response: [%s] %s (%s)", $soapMessage->getFaultCode(), $soapMessage->getFaultString(), $soapMessage->getFaultDetail()));
            return false;
        }
        
        return true;
    }
}