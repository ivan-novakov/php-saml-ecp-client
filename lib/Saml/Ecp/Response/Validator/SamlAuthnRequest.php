<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


/**
 * Checks if the body contains the AuthnRequest element.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class SamlAuthnRequest extends AbstractValidator
{

    /**
     * Option index.
     */
    const OPT_ELEMENT_NAME = 'element_name';

    /**
     * Option index.
     */
    const OPT_ELEMENT_NS = 'element_ns';

    /**
     * Default authn request element name.
     * 
     * @var string
     */
    protected $_defElementName = 'AuthnRequest';

    /**
     * Default authn request namespace.
     * 
     * @var string
     */
    protected $_defElementNs = 'urn:oasis:names:tc:SAML:2.0:protocol';


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid(ResponseInterface $response)
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