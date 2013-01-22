<?php

namespace Saml\Ecp\Soap\Exception;


/**
 * Runtime exception thrown when there is an unregistered namespace in a DOM document.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class InvalidNamespaceException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param string $prefix The namespace prefix.
     */
    public function __construct($prefix)
    {
        parent::__construct(sprintf("Invalid namespace '%s'", $prefix));
    }
}