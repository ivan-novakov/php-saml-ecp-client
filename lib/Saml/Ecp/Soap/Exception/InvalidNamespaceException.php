<?php

namespace Saml\Ecp\Soap\Exception;


class InvalidNamespaceException extends \RuntimeException
{


    public function __construct ($prefix)
    {
        parent::__construct(sprintf("Invalid namespace '%s'", $prefix));
    }
}