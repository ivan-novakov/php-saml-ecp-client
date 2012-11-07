<?php

namespace Saml\Ecp\Exception;


class MissingConfigException extends \RuntimeException
{


    public function __construct ($configFieldName)
    {
        parent::__construct(sprintf("Missing config field '%s'", $configFieldName));
    }
}