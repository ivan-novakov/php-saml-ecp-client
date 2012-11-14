<?php

namespace Saml\Ecp\Soap\Container;

use Saml\Ecp\Soap\Message;


/**
 * Interface for objects which contain a SOAP message, typically ECP requests or response objects.
 *
 */
interface ContainerInterface
{


    /**
     * Sets a SOAP message to the container.
     * 
     * @param Message $soapMessage
     */
    public function setSoapMessage (Message $soapMessage);


    /**
     * Returns the SOAP message from the container.
     * 
     * @return Message
     */
    public function getSoapMessage ();
}