<?php

namespace Saml\Ecp\Exception;


/**
 * Runtime exception thrown by an object when a required dependency is missing.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class MissingDependencyException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param string $dependency
     */
    public function __construct($dependency)
    {
        parent::__construct(sprintf("Missing dependency '%s'", $dependency));
    }
}