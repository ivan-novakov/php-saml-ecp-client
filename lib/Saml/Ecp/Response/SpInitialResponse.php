<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Client\MimeType;


class SpInitialResponse extends AbstractResponse
{


    public function getPaosRequestService ()
    {
        return $this->getSoapMessage()
            ->getNodeValueByXpath('/S:Envelope/S:Header/paos:Request/@service');
    }


    public function getPaosResponseConsumerUrl ()
    {
        return $this->getSoapMessage()
            ->getNodeValueByXpath('/S:Envelope/S:Header/paos:Request/@responseConsumerURL');
    }


    public function getAssertionConsumerServiceUrl ()
    {
        return $this->getSoapMessage()
            ->getNodeValueByXpath('/S:Envelope/S:Body/samlp:AuthnRequest/@AssertionConsumerServiceURL');
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
        $soapMessage = $this->getSoapMessage();
        
        return $soapMessage->getNodeValueByXpath('/S:Envelope/S:Header/ecp:Request/saml:Issuer');
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
        $soapMessage = $this->getSoapMessage();
        
        $nodes = $soapMessage->xpathQuery('/S:Envelope/S:Header/ecp:Request/samlp:IDPList/samlp:IDPEntry/@ProviderID');
        
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
        return $this->getSoapMessage()
            ->getNodeValueByXpath('/S:Envelope/S:Header/ecp:RelayState');
    }
}