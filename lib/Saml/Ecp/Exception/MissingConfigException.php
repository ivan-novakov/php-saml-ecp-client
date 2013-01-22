<?php

namespace Saml\Ecp\Exception;


/**
 * Runtime exception thrown by an object when there is a missing required configuration directive.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class MissingConfigException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param string $configFieldName
     */
    public function __construct($configFieldName)
    {
        parent::__construct(sprintf("Missing config field '%s'", $configFieldName));
    }
}