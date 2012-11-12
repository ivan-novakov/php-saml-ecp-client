<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\Message;


interface ResponseInterface
{


    /**
     * Sets the HTTP response.
     * 
     * @param \Zend\Http\Response $response
     */
    public function setHttpResponse (\Zend\Http\Response $response);


    /**
     * Returns the content of the response.
     *
     * @return string
     */
    public function getContent ();


    /**
     * Performs validation of the response data.
     * 
     * @throws Exception\InvalidResponseException
     */
    public function validate ();
}