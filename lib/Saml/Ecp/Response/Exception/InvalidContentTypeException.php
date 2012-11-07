<?php

namespace Saml\Ecp\Response\Exception;


class InvalidContentTypeException extends \RuntimeException
{


    public function __construct ($contentType)
    {
        parent::__construct(sprintf("Invalid Content-Type '%s'", $contentType));
    }
}