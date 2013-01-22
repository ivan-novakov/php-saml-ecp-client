<?php

namespace Saml\Ecp\Exception;


/**
 * Runtime exception indicating that the instance does not implement the required interface.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class InterfaceNotImplementedException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param mixed $instance
     * @param string $interfaceName
     */
    public function __construct($instance, $interfaceName)
    {
        parent::__construct(sprintf("The instance of '%s' does not implement interface '%s'", get_class($instance), $interfaceName));
    }
}