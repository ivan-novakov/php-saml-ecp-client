<?php

namespace Saml\Ecp\Flow;

use Saml\Ecp\Exception as GeneralException;
use Saml\Ecp\Client\Client;
use Saml\Ecp\Authentication;
use Saml\Ecp\Discovery;


class Basic implements FlowInterface
{

    /**
     * The ECP client.
     * 
     * @var Client
     */
    protected $_client = null;


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
     * (non-PHPdoc)
     * @see \Saml\Ecp\Flow\FlowInterface::authenticate()
     */
    public function authenticate (Authentication\Method\MethodInterface $authenticationMethod, 
        Discovery\Method\MethodInterface $discoveryMethod)
    {
        $client = $this->getClient();
        $requestFactory = $client->getRequestFactory();
        
        // send PAOS request to SP
        $spInitialRequest = $requestFactory->createSpInitialRequest($client->getProtectedContentUri(true));
        $spInitialResponse = $client->sendInitialRequestToSp($spInitialRequest);
        
        // send authn request to IdP
        $idpAuthnRequest = $requestFactory->createIdpAuthnRequest($spInitialResponse, $discoveryMethod->getIdpEcpEndpoint());
        $idpAuthnResponse = $client->sendAuthnRequestToIdp($idpAuthnRequest, $authenticationMethod);
        
        // convey the authn response back to the SP
        $spConveyRequest = $requestFactory->createSpAuthnConveyRequest($idpAuthnResponse, $idpAuthnResponse->getConsumerEndpointUrl());
        $spConveyResponse = $client->sendAuthnResponseToSp($spConveyRequest);
        
        // access protected resource
        $spResourceRequest = $requestFactory->createSpResourceRequest($client->getProtectedContentUri());
        $spResourceResponse = $client->sendResourceRequestToSp($spResourceRequest);
        
        return $spResourceResponse;
    }
}