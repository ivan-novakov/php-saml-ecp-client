<?php

namespace Saml\Ecp\Exception;


class MissingDependencyException extends \RuntimeException
{


    public function __construct ($dependency)
    {
        parent::__construct(sprintf("Missing dependency '%s'", $dependency));
    }
}