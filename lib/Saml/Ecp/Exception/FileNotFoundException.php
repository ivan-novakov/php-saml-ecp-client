<?php

namespace Saml\Ecp\Exception;


class FileNotFoundException extends \RuntimeException
{


    public function __construct ($filename)
    {
        parent::__construct(sprintf("File not found: '%s'", $filename));
    }
}