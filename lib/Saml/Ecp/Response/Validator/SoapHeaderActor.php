<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


/**
 * Validates that all SOAP header blocks contain the required attributes with their corresponding values:
 * 
 *   - "actor" with value "http://schemas.xmlsoap.org/soap/actor/next"
 *   - "mustUnderstand" with value "1"
 *
 */
class SoapHeaderActor extends AbstractValidator
{

    protected $_soapEnvelopeNamespaceUri = 'http://schemas.xmlsoap.org/soap/envelope/';

    protected $_requiredAttributeValues = array(
        'actor' => 'http://schemas.xmlsoap.org/soap/actor/next', 
        'mustUnderstand' => '1'
    );


    /**
     * Sets the required attribute values.
     * 
     * @param array $requiredAttributeValues
     */
    public function setRequiredAttributeValues (array $requiredAttributeValues)
    {
        $this->_requiredAttributeValues = $requiredAttributeValues;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid (ResponseInterface $response)
    {
        $valid = false;
        
        try {
            $soapMessage = $response->getSoapMessage();
        } catch (\Exception $e) {
            $this->addMessage(sprintf("Error loading SOAP message: [%s] %s", get_class($e), $e->getMessage()));
            return $valid;
        }
        
        $elements = $soapMessage->getHeaderElements();
        
        $invalidAttributes = 0;
        foreach ($elements as $element) {
            /* @var $element \DomElement */
            foreach ($this->_requiredAttributeValues as $name => $value) {
                $attributeValue = $element->getAttributeNS($this->_soapEnvelopeNamespaceUri, $name);
                if ($attributeValue != $value) {
                    $this->addMessage(sprintf("Header element '%s' has attribute '%s' with wrong value '%s', expected '%s'", $element->nodeName, $name, $attributeValue, $value));
                    $invalidAttributes ++;
                }
            }
        }
        
        if (! $invalidAttributes) {
            $valid = true;
        }
        
        return $valid;
    }
}