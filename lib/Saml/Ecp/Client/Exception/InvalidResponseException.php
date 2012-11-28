<?php

namespace Saml\Ecp\Client\Exception;

use Saml\Ecp\Response\ResponseInterface;


class InvalidResponseException extends \RuntimeException
{

    /**
     * The response object.
     * 
     * @var ResponseInterface
     */
    protected $_response = null;

    /**
     * A list of validation error messages.
     * 
     * @var array
     */
    protected $_messages = array();


    /**
     * Constructor.
     * 
     * @param ResponseInterface $response
     * @param array $messages
     */
    public function __construct (ResponseInterface $response, array $messages)
    {
        $this->_response = $response;
        $this->_messages = $messages;
        
        parent::__construct(sprintf("Invalid Response: [%s]: %s", get_class($response), implode(', ', $messages)));
    }


    /**
     * Returns the invalid response.
     * 
     * @return ResponseInterface
     */
    public function getResponse ()
    {
        return $this->_response;
    }


    /**
     * Returns the validation error messages.
     * @return array
     */
    public function getMessages ()
    {
        return $this->_messages;
    }
}