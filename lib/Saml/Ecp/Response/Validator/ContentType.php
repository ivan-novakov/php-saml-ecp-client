<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Exception as GeneralException;
use Saml\Ecp\Response\ResponseInterface;


class ContentType extends AbstractValidator
{

    const OPT_EXPECTED_CONTENT_TYPE = 'expected_content_type';

    const OPT_PARTIAL = 'partial';


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid (ResponseInterface $response)
    {
        $partial = $this->getOption(self::OPT_PARTIAL);
        $expectedContentType = $this->getOption(self::OPT_EXPECTED_CONTENT_TYPE);
        if (null === $expectedContentType) {
            throw new GeneralException\MissingOptionException(self::OPT_EXPECTED_CONTENT_TYPE);
        }
        
        $contentType = $response->getHttpResponse()
            ->getHeaders()
            ->get('Content-Type')
            ->getFieldValue();
        
        if ($partial) {
            $parts = explode(';', $contentType);
            if ($parts[0] != $expectedContentType) {
                $this->addMessage(sprintf("Content type '%s' is different from the expected '%s' (partial comparison)", $contentType, $expectedContentType));
                return false;
            }
        } else {
            if ($contentType != $expectedContentType) {
                $this->addMessage(sprintf("Content type '%s' is different from the expected '%s'", $contentType, $expectedContentType));
                return false;
            }
        }
        
        return true;
    }
}