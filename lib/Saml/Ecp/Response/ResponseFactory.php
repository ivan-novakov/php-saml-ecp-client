<?php

namespace Saml\Ecp\Response;


/**
 * "Standard" implementation of the response factory interface.
 * 
 * @copyright (c) 2013 Ivan Novakov (http://novakov.cz/)
 * @license http://debug.cz/license/freebsd
 */
class ResponseFactory implements ResponseFactoryInterface
{


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createSpInitialResponse()
     */
    public function createSpInitialResponse(\Zend\Http\Response $httpResponse)
    {
        return new SpInitialResponse($httpResponse);
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createIdpAuthnResponse()
     */
    public function createIdpAuthnResponse(\Zend\Http\Response $httpResponse)
    {
        return new IdpAuthnResponse($httpResponse);
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createSpConveryAuthnResponse()
     */
    public function createSpConveryAuthnResponse(\Zend\Http\Response $httpResponse)
    {
        return new SpConveyAuthnResponse($httpResponse);
    }


    /**
     * {@inheritdoc}
     * @see \Saml\Ecp\Response\ResponseFactoryInterface::createSpResourceResponse()
     */
    public function createSpResourceResponse(\Zend\Http\Response $httpResponse)
    {
        return new SpResourceResponse($httpResponse);
    }
}