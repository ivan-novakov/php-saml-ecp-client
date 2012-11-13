<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Client\MimeType;


class SpInitialResponse extends AbstractResponse
{


    public function validate ()
    {
        $this->_validateStatusCode();
        
        $httpResponse = $this->getHttpResponse();
        
        $contentType = $httpResponse->getHeaders()
            ->get('Content-Type');
        
        if (! $contentType || $contentType->getFieldValue() != MimeType::PAOS) {
            throw new Exception\InvalidContentTypeException($contentType);
        }
        
        try {
            $soapMessage = $this->getSoapMessage();
        } catch (\Exception $e) {
            throw new Exception\MissingSoapMessageException(sprintf("Error loading SOAP message: [%s] %s", get_class($e), $e->getMessage()));
        }
    }
}