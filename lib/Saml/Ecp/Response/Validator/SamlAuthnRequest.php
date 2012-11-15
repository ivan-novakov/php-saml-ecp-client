<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


/**
 * Checks if the body contains the AuthnRequest element.
 *
 */
class SamlAuthnRequest extends AbstractValidator
{

    const OPT_ELEMENT_NAME = 'element_name';

    const OPT_ELEMENT_NS = 'element_ns';

    protected $_defElementName = 'AuthnRequest';

    protected $_defElementNs = 'urn:oasis:names:tc:SAML:2.0:protocol';


    public function isValid (ResponseInterface $response)
    {
        $soapMessage = $response->getSoapMessage();
        
        $bodyElements = $soapMessage->getBodyElements();
        if (! $bodyElements->length) {
            $this->addMessage('No body elements');
            return false;
        }
        
        $element = $bodyElements->item(0);
        $elementPrefix = $element->prefix;
        $elementName = $element->localName;
        
        $expectedElementName = $this->getOption(self::OPT_ELEMENT_NAME, $this->_defElementName);
        if ($elementName != $expectedElementName) {
            $this->addMessage(sprintf("The element name '%s' does not correspond to the expected '%s'", $elementName, $expectedElementName));
            return false;
        }
        
        $expectedElementNs = $this->getOption(self::OPT_ELEMENT_NS, $this->_defElementNs);
        $elementNs = $element->getAttribute('xmlns:' . $elementPrefix);
        if ($elementNs != $expectedElementNs) {
            $this->addMessage(sprintf("The element namespace '%s' does not correspond to the expected '%s'", $elementNs, $expectedElementNs));
            return false;
        }
        
        return true;
    }
}