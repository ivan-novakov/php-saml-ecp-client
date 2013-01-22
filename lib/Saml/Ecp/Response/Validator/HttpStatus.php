<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


/**
 * Checks if the response contains valid HTTP status.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class HttpStatus extends AbstractValidator
{

    /**
     * Options index.
     */
    const OPT_EXPECTED_STATUS = 'expected_status';

    /**
     * Default expected status.
     * 
     * @var integer
     */
    protected $_defaultExpectedStatus = 200;


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\Validator\ValidatorInterface::isValid()
     */
    public function isValid(ResponseInterface $response)
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