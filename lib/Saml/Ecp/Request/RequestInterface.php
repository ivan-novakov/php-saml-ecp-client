<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Container\ContainerInterface;


interface RequestInterface extends ContainerInterface
{


    /**
     * Sets the target URI of the request.
     * 
     * @param string $uri
     */
    public function setUri ($uri);


    /**
     * Returns the target URI of the request.
     * 
     * @return string
     */
    public function getUri ();


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