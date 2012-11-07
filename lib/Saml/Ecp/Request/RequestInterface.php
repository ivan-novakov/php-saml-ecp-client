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


    /**
     * Sets the SOAP message to be sent.
     * 
     * @param Message $message
     */
    public function setSoapMessage (Message $message);
}