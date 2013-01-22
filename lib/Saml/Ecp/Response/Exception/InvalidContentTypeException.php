<?php

namespace Saml\Ecp\Response\Exception;


/**
 * Runtime exception thrown when the remote server returns invalid content type.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class InvalidContentTypeException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param string $contentType
     */
    public function __construct($contentType)
    {
        parent::__construct(sprintf("Invalid Content-Type '%s'", $contentType));
    }
}