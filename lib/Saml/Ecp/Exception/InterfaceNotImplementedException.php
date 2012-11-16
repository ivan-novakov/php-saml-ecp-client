<?php

namespace Saml\Ecp\Exception;


class InterfaceNotImplementedException extends \RuntimeException
{


    public function __construct ($instance, $interfaceName)
    {
        parent::__construct(sprintf("The instance of '%s' does not implement interface '%s'", get_class($instance), $interfaceName));
    }
}