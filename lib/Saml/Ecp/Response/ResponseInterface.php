<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\ContainerInterface;
use Saml\Ecp\Soap\Message;


interface ResponseInterface extends ContainerInterface
{


    /**
     * Sets the HTTP response.
     * 
     * @param \Zend\Http\Response $response
     */
    public function setHttpResponse (\Zend\Http\Response $response);


    /**
     * Returns the HTTP response.
     * 
     * @return \Zend\Http\Response
     */
    public function getHttpResponse ();


    /**
     * Returns the content of the response.
     *
     * @return string
     */
    public function getContent ();
}