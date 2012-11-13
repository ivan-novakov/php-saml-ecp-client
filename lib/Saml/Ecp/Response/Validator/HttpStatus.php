<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


class HttpStatus extends AbstractValidator
{

    const OPT_EXPECTED_STATUS = 'expected_status';

    protected $_defaultExpectedStatus = 200;


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::validate()
     */
    public function isValid (ResponseInterface $response)
    {
        $expectedStatus = $this->getOption(self::OPT_EXPECTED_STATUS, $this->_defaultExpectedStatus);
        $status = $response->getHttpResponse()
            ->getStatusCode();
        
        if ($status != $expectedStatus) {
            $this->addMessage(sprintf("HTTP status code %d is different from the expected %d", $status, $expectedStatus));
            return false;
        }
        
        return true;
    }
}