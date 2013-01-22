<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Soap\Container\ContainerInterface;
use Saml\Ecp\Soap\Message;


/**
 * Response interface.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface ResponseInterface extends ContainerInterface
{


    /**
     * Sets the HTTP response.
     * 
     * @param \Zend\Http\Response $response
     */
    public function setHttpResponse(\Zend\Http\Response $response);


    /**
     * Returns the HTTP response.
     * 
     * @return \Zend\Http\Response
     */
    public function getHttpResponse();


    /**
     * Returns the content of the response.
     *
     * @return string
     */
    public function getContent();
}