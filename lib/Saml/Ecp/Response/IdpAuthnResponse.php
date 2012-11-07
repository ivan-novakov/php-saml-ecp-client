<?php

namespace Saml\Ecp\Response;


class IdpAuthnResponse extends Response
{


    public function validate (array $validateOptions = array())
    {
        $this->_validateStatusCode();
        
        // validate AssertionConsumerServiceURL
        //$soapMessage = $this->getSoapMessage();
        //_dumpx($soapMessage->toString());
    }
}