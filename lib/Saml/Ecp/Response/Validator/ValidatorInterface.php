<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


interface ValidatorInterface
{


    /**
     * Validates a response.
     * 
     * @param ResponseInterface $response
     * @return boolean
     */
    public function isValid (ResponseInterface $response);


    /**
     * Returns validation errors if any.
     * 
     * @return array
     */
    public function getMessages ();
}