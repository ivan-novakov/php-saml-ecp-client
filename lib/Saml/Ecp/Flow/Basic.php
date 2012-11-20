<?php

namespace Saml\Ecp\Flow;

use Saml\Ecp\Exception as GeneralException;
use Saml\Ecp\Client\Client;
use Saml\Ecp\Authentication;
use Saml\Ecp\Discovery;
use Saml\Ecp\Request;


class Basic implements FlowInterface
{

    /**
     * The ECP client.
     * 
     * @var Client
     */
    protected $_client = null;

    /**
     * The request factory object.
     *
     * @var Request\RequestFactoryInterface
     */
    protected $_requestFactory = null;


    /**
     * Returns the ECP client.
     *
     * @throws GeneralException\MissingDependencyException
     * @return Client
     */
    public function getClient ()
    {
        if (! ($this->_client instanceof Client)) {
            throw new GeneralException\MissingDependencyException('client');
        }
        return $this->_client;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Flow\FlowInterface::setClient()
     */
    public function setClient (Client $client)
    {
        $this->_client = $client;
    }


    /**
     * Returns the request factory object.
     *
     * @return Request\RequestFactoryInterface
     */
    public function getRequestFactory ()
    {
        if (! ($this->_requestFactory instanceof Request\RequestFactoryInterface)) {
            $this->_requestFactory = new Request\RequestFactory();
        }
        
        return $this->_requestFactory;
    }


    /**
     * Sets the request factory object.
     *
     * @param Request\RequestFactoryInterface $requestFactory
     */
    public function setRequestFactory (Request\RequestFactoryInterface $requestFactory)
    {
        $this->_requestFactory = $requestFactory;
    }


    /**
     * (non-PHPdoc)
     * @see \Saml\Ecp\Flow\FlowInterface::authenticate()
     */
    public function authenticate ($protectedContentUrl, Discovery\Method\MethodInterface $discoveryMethod, 
        Authentication\Method\MethodInterface $authenticationMethod)
    {
        $client = $this->getClient();
        $requestFactory = $this->getRequestFactory();
        
        // send PAOS request to SP
        $spInitialRequest = $requestFactory->createSpInitialRequest($protectedContentUrl);
        $spInitialResponse = $client->sendInitialRequestToSp($spInitialRequest);
        
        // send authn request to IdP
        $idpAuthnRequest = $requestFactory->createIdpAuthnRequest($spInitialResponse, $discoveryMethod->getIdpEcpEndpoint());
        $idpAuthnResponse = $client->sendAuthnRequestToIdp($idpAuthnRequest, $authenticationMethod);
        
        // convey the authn response back to the SP
        $spConveyRequest = $requestFactory->createSpAuthnConveyRequest($idpAuthnResponse, $idpAuthnResponse->getConsumerEndpointUrl());
        $spConveyResponse = $client->sendAuthnResponseToSp($spConveyRequest);
        
        // access protected resource
        $spResourceRequest = $requestFactory->createSpResourceRequest($protectedContentUrl);
        $spResourceResponse = $client->sendResourceRequestToSp($spResourceRequest);
        
        return $spResourceResponse;
    }
}