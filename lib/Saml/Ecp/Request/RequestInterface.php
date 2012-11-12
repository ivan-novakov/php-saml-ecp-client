<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Message;


interface RequestInterface
{


    /**
     * Sets the target URI of the request.
     * 
     * @param string $uri
     */
    public function setUri ($uri);


    /**
     * Returns the HTTP request.
     * 
     * @return \Zend\Http\Request
     */
    public function getHttpRequest ();
}