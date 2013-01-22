<?php

namespace Saml\Ecp\Exception;


/**
 * Runtime exception thrown when an object is missing a require option.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class MissingOptionException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param string $optionName
     */
    public function __construct($optionName)
    {
        parent::__construct(sprintf("Missing option '%s'", $optionName));
    }
}