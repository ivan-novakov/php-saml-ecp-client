<?php

namespace Saml\Ecp\Response;


/**
 * "Standard" implementation of the response factory interface.
 *
 */
class ResponseFactory implements ResponseFactoryInterface
{


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createSpInitialResponse()
     */
    public function createSpInitialResponse (\Zend\Http\Response $httpResponse)
    {
        return new SpInitialResponse($httpResponse);
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createIdpAuthnResponse()
     */
    public function createIdpAuthnResponse (\Zend\Http\Response $httpResponse)
    {
        return new IdpAuthnResponse($httpResponse);
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createSpConveryAuthnResponse()
     */
    public function createSpConveryAuthnResponse (\Zend\Http\Response $httpResponse)
    {
        return new SpConveyAuthnResponse($httpResponse);
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createSpResourceResponse()
     */
    public function createSpResourceResponse (\Zend\Http\Response $httpResponse)
    {
        return new SpResourceResponse($httpResponse);
    }
}