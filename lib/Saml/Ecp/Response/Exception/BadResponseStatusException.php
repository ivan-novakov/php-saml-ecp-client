<?php

namespace Saml\Ecp\Response\Exception;


class BadResponseStatusException extends \RuntimeException
{


    public function __construct ($statusCode)
    {
        parent::__construct(sprintf("Bad response status '%s'", $statusCode));
    }
}