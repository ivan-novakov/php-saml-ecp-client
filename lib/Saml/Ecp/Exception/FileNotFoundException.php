<?php

namespace Saml\Ecp\Exception;


/**
 * Runtime exception indicating missing file.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class FileNotFoundException extends \RuntimeException
{


    /**
     * Constructor.
     * 
     * @param string $filename
     */
    public function __construct($filename)
    {
        parent::__construct(sprintf("File not found: '%s'", $filename));
    }
}