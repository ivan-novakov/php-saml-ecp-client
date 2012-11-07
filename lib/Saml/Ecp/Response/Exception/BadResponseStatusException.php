<?php

namespace Saml\Ecp\Response\Exception;


class BadResponseStatusException extends \RuntimeException
{


    public function __construct ($statuCode)
    {
        parent::__construct(sprintf("Bad response status '%s'", $statusCode));
    }
}