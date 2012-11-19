<?php

namespace Saml\Ecp\Exception;


class MissingMethodOptionException extends \RuntimeException
{


    public function __construct ($optionName, $methodName)
    {
        parent::__construct(sprintf("Missing option '%s' in method '%s'", $optionName, $methodName));
    }
}