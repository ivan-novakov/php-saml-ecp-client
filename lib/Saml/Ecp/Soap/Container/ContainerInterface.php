<?php

namespace Saml\Ecp\Soap\Container;

use Saml\Ecp\Soap\Message\Message;


/**
 * Interface for objects which contain a SOAP message, typically ECP requests or response objects.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface ContainerInterface
{


    /**
     * Sets a SOAP message to the container.
     * 
     * @param Message $soapMessage
     */
    public function setSoapMessage(Message $soapMessage);


    /**
     * Returns the SOAP message from the container.
     * 
     * @return Message
     */
    public function getSoapMessage();
}