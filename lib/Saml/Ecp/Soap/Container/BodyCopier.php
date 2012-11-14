<?php

namespace Saml\Ecp\Soap\Container;


class BodyCopier
{


    /**
     * Copies the SOAP body part from one SOAP container to another.
     * 
     * @param ContainerInterface $fromContainer
     * @param ContainerInterface $toContainer
     */
    public function copyBody (ContainerInterface $fromContainer, ContainerInterface $toContainer)
    {
        $fromSoap = $fromContainer->getSoapMessage();
        
        $toSoap = $toContainer->getSoapMessage();
        $toSoap->copyBodyFromMessage($fromSoap);
        
        $toContainer->setSoapMessage($toSoap);
    }
}