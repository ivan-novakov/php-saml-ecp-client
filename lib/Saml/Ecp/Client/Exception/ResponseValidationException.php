<?php

namespace Saml\Ecp\Client\Exception;

use \Exception;
use Saml\Ecp\Response\Validator\ValidatorInterface;
use Saml\Ecp\Response\ResponseInterface;


/**
 * Thrown when there is an unexpected error during response validation.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class ResponseValidationException extends \RuntimeException
{

    /**
     * The response.
     * 
     * @var ResponseInterface
     */
    protected $_response = null;

    /**
     * The validator used to validate the response.
     * 
     * @var ValidatorInterface
     */
    protected $_validator = null;


    /**
     * Constructor.
     * 
     * @param ResponseInterface $response The response object.
     * @param ValidatorInterface $validator The validator object.
     * @param Exception $previousException The validation exception.
     */
    public function __construct(ResponseInterface $response, ValidatorInterface $validator, Exception $previousException)
    {
        $this->_response = $response;
        $this->_validator = $validator;
        
        parent::__construct(sprintf("Exception during validation response (%s): [%s] %s", get_class($response), get_class($previousException), $previousException->getMessage()), null, $previousException);
    }


    /**
     * Returns the response.
     * 
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->_response;
    }


    /**
     * Returns the validator.
     * 
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->_validator;
    }
}