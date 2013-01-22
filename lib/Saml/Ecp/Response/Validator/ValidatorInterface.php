<?php

namespace Saml\Ecp\Response\Validator;

use Saml\Ecp\Response\ResponseInterface;


/**
 * Response validator interface.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
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