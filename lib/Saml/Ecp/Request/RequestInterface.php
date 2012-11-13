<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\ContainerInterface;


interface RequestInterface extends ContainerInterface
{


    /**
     * Sets the target URI of the request.
     * 
     * @param string $uri
     */
    public function setUri ($uri);


    /**
     * Sets the content of the request.
     * 
     * @param string $content
     */
    public function setContent ($content);


    /**
     * Returns the HTTP request.
     * 
     * @return \Zend\Http\Request
     */
    public function getHttpRequest ();
}