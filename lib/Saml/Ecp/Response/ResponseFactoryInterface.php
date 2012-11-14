<?php

namespace Saml\Ecp\Response;

use Saml\Ecp\Request\SpResourceRequest;


/**
 * Interface for response factories.
 *
 */
interface ResponseFactoryInterface
{


    /**
     * Creates a SP initial response.
     * 
     * @param \Zend\Http\Response $httpResponse
     * @return SpInitialResponse
     */
    public function createSpInitialResponse (\Zend\Http\Response $httpResponse);


    /**
     * Creates an IdP authn response.
     * 
     * @param \Zend\Http\Response $httpResponse
     * @return IdpAuthnResponse
     */
    public function createIdpAuthnResponse (\Zend\Http\Response $httpResponse);


    /**
     * Creates a SP convey authn response.
     * 
     * @param \Zend\Http\Response $httpResponse
     * @return SpConveyAuthnResponse
     */
    public function createSpConveryAuthnResponse (\Zend\Http\Response $httpResponse);


    /**
     * Creates a SP resource request.
     * 
     * @param \Zend\Http\Response $httpResponse
     * @return SpResourceResponse
     */
    public function createSpResourceResponse (\Zend\Http\Response $httpResponse);
}