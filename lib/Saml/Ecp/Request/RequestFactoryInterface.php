<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\Container\ContainerInterface;


/**
 * Request factory interface.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
interface RequestFactoryInterface
{


    /**
     * Creates the initial request to the SP.
     * 
     * @param string $protectedContentUri
     * @return RequestInterface
     */
    public function createSpInitialRequest ($protectedContentUri);


    /**
     * Creates an authn request to be sent to the IdP, based on the provided SOAP data.
     *
     * @param ContainerInterface $soapContainer
     * @param string $idpEndpointUrl
     * @return RequestInterface
     */
    public function createIdpAuthnRequest (ContainerInterface $soapContainer, $idpEndpointUrl);


    /**
     * Creates an authn response to be sent to the SP, based on the provided SOAP data.
     * 
     * @param ContainerInterface $soapContainer
     * @param string $spEndpointUrl
     * @return RequestInterface
     */
    public function createSpAuthnConveyRequest (ContainerInterface $soapContainer, $spEndpointUrl);


    /**
     * Creates a resource request to the SP.
     * 
     * @param string $resourceUri
     * @return RequestInterface
     */
    public function createSpResourceRequest ($resourceUri);
}