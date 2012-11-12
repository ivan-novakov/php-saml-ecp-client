<?php

namespace Saml\Ecp\Request;

use Saml\Ecp\Soap\ContainerInterface;


class RequestFactory implements RequestFactoryInterface
{


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createSpInitialRequest()
     * @return SpInitialRequest
     */
    public function createSpInitialRequest ()
    {
        return new SpInitialRequest();
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createIdpAuthnRequest()
     * @return IdpAuthnRequest
     */
    public function createIdpAuthnRequest (ContainerInterface $soapContainer, $idpEndpointUrl)
    {
        $request = new IdpAuthnRequest();
        $request->copyDataFromSoap($soapContainer);
        $request->setUri($idpEndpointUrl);
        
        return $request;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createSpAuthnConveyRequest()
     * @return SpConveyAuthnRequest
     */
    public function createSpAuthnConveyRequest (ContainerInterface $soapContainer, $spEndpointUrl)
    {
        $request = new SpConveyAuthnRequest();
        $request->copyDataFromSoap($soapContainer);
        $request->setUri($spEndpointUrl);
        
        return $request;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Request\RequestFactoryInterface::createSpResourceRequest()
     * @return SpResourceRequest
     */
    public function createSpResourceRequest ($resourceUri)
    {
        $request = new SpResourceRequest();
        $request->setUri($resourceUri);
        
        return $request;
    }
}