<?php

namespace Saml\Ecp\Soap\Message;


class AuthnRequest extends Message
{


    /**
     * Returns the "service" attribute of the "paos:Request" header element.
     * 
     * XPath: /S:Envelope/S:Header/paos:Request/@service
     * 
     * @return string
     */
    public function getPaosRequestService ()
    {
        return $this->getNodeValueByXpath('/S:Envelope/S:Header/paos:Request/@service');
    }


    /**
     * Returns the "responseConsumerURL" attribute of the "paos:Request" header element;
     * 
     * XPath: /S:Envelope/S:Header/paos:Request/@responseConsumerURL
     * 
     * @return string
     */
    public function getPaosResponseConsumerUrl ()
    {
        return $this->getNodeValueByXpath('/S:Envelope/S:Header/paos:Request/@responseConsumerURL');
    }


    /**
     * Returns the "AssertionConsumerServiceURL" attribute of the "samlp:AuthnRequest" body element.
     * 
     * XPath: /S:Envelope/S:Body/samlp:AuthnRequest/@AssertionConsumerServiceURL
     * 
     * @return string
     */
    public function getAssertionConsumerServiceUrl ()
    {
        return $this->getNodeValueByXpath('/S:Envelope/S:Body/samlp:AuthnRequest/@AssertionConsumerServiceURL');
    }


    /**
     * Returns the service provider's (self-asserted) human-readable name.
     *
     * XPath: /S:Envelope/S:Header/ecp:Request/saml:Issuer
     *
     * @return string|null
     */
    public function getSpName ()
    {
        return $this->getNodeValueByXpath('/S:Envelope/S:Header/ecp:Request/saml:Issuer');
    }


    /**
     * Returns a list of IdP entity IDs as specified in the header element.
     *
     * XPath: /S:Envelope/S:Header/ecp:Request/samlp:IDPList/samlp:IDPEntry/@ProviderID
     *
     * @return array
     */
    public function getIdPList ()
    {
        $nodes = $this->xpathQuery('/S:Envelope/S:Header/ecp:Request/samlp:IDPList/samlp:IDPEntry/@ProviderID');
        
        $list = array();
        foreach ($nodes as $node) {
            $list[] = $node->nodeValue;
        }
        
        return $list;
    }


    /**
     * Returns the RelayState header element value.
     *
     * XPath: /S:Envelope/S:Header/ecp:RelayState
     *
     * @return string|null
     */
    public function getRelayState ()
    {
        return $this->getNodeValueByXpath('/S:Envelope/S:Header/ecp:RelayState');
    }
}