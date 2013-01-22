<?php

namespace Saml\Ecp\Soap\Container;


/**
 * A simple class which copies the SOAP message body from one SOAP container to another.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class BodyCopier
{


    /**
     * Copies the SOAP body part from one SOAP container to another.
     * 
     * @param ContainerInterface $fromContainer
     * @param ContainerInterface $toContainer
     */
    public function copyBody(ContainerInterface $fromContainer, ContainerInterface $toContainer)
    {
        $fromSoap = $fromContainer->getSoapMessage();
        
        $toSoap = $toContainer->getSoapMessage();
        $toSoap->copyBodyFromMessage($fromSoap);
        
        $toContainer->setSoapMessage($toSoap);
    }
}