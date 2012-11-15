<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


/**
 * Validates the PAOS header block.
 * 
 * From the specs:
 * A <paos:Request> SOAP header block element (see Section 10 of [PAOS]). Its content MUST be as follows:
 *
 *   - service MUST be set to "urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp" 
 *   
 *   - responseConsumerURL MUST contain an absolute URL that specifies where error responses generated by the client 
 *   should be sent; it MUST match the value of the AssertionServiceConsumerURL attribute in the <samlp:AuthnRequest> 
 *   (or in its absence the location to which the identity provider is expected to target its response, 
 *   such as a location derived from SAML metadata).
 *
 */
class PaosRequest extends AbstractValidator
{

    const OPT_SERVICE_VALUE = 'service_value';

    protected $_defServiceValue = 'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp';


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid (ResponseInterface $response)
    {
        $valid = true;
        
        $soapMessage = $response->getSoapMessage();
        
        $expectedService = $this->getOption(self::OPT_SERVICE_VALUE, $this->_defServiceValue);
        $service = $soapMessage->getPaosRequestService();
        if ($service != $expectedService) {
            $this->addMessage(sprintf("paos:Request element declares service '%s' different from the expected '%s'", $service, $expectedService));
            $valid = false;
        }
        
        $paosConsumerUrl = $soapMessage->getPaosRequestResponseConsumerUrl();
        if (null === $paosConsumerUrl) {
            $this->addMessage('The paos:Request/@responseConsumerURL must not be null');
            $valid = false;
        }
        
        $samlAuthnRequestConsumerUrl = $soapMessage->getAuthnRequestAssertionConsumerServiceUrl();
        if (null === $samlAuthnRequestConsumerUrl) {
            $this->addMessage('The samlp:AuthnRequest/@AssertionConsumerServiceURL must not be null');
            $valid = false;
        }
        
        if ($paosConsumerUrl != $samlAuthnRequestConsumerUrl) {
            $this->addMessage(sprintf("paos:Request/@responseConsumerURL with value '%s' is different from samlp:AuthnRequest/@AssertionConsumerServiceURL with value '%s'", $paosConsumerUrl, $samlAuthnRequestConsumerUrl));
            $valid = false;
        }
        
        return $valid;
    }
}