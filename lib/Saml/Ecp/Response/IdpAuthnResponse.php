<?php

namespace Saml\Ecp\Response;


class IdpAuthnResponse extends Response
{


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Response::validate()
     */
    public function validate (array $validateOptions = array())
    {
        $this->_validateStatusCode();
        
        // validate AssertionConsumerServiceURL
        //$soapMessage = $this->getSoapMessage();
        //_dumpx($soapMessage->toString());
    }


    /**
     * Returns the SP endpoint to which the response needs to be delivered.
     * 
     * @throws Exception\InvalidResponseException
     * @return string
     */
    public function getConsumerEndpointUrl ()
    {
        $responseElement = $this->_getSamlResponseXmlElement();
        if (! $responseElement) {
            throw new Exception\InvalidResponseException("The SAML response does not contain a 'Response' element");
        }
        
        $url = $responseElement->getAttribute('Destination');
        if (! $url) {
            throw new Exception\InvalidResponseException("No 'Destination' attribute in the 'Response' element");
        }
        
        return $url;
    }


    /**
     * Returns the SAML2 Response element.
     * 
     * @return \DomElement|null
     */
    protected function _getSamlResponseXmlElement ()
    {
        $xpath = $this->getSoapMessage()
            ->getXpath();
        
        $nodes = $xpath->query('/S:Envelope/S:Body/samlp:Response');
        if ($nodes->length) {
            return $nodes->item(0);
        }
        
        return null;
    }
}