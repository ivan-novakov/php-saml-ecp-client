<?php

namespace Saml\Ecp\Response\Exception;


/**
 * Runtime exception thrown when the remote server returns a HTTP status error.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class BadResponseStatusException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param string $statusCode
     */
    public function __construct($statusCode)
    {
        parent::__construct(sprintf("Bad response status '%s'", $statusCode));
    }
}