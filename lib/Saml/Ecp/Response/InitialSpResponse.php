<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Client\MimeType;


class InitialSpResponse extends Response
{


    public function validate ()
    {
        $this->_validateStatusCode();
        
        $httpResponse = $this->getHttpResponse();
        
        $contentType = $httpResponse->getHeaders()
            ->get('Content-Type');
        
        if (! $contentType) {
            throw new Exception\InvalidResponseException('Unknown content type');
        }
        
        if ($contentType->getFieldValue() != MimeType::PAOS) {
            throw new Exception\InvalidContentTypeException($contentType);
        }
    }
}