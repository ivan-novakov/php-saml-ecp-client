<?php

namespace Saml\Ecp\Client;

use Saml\Ecp\Response;
use Saml\Ecp\Request;
use Saml\Ecp\Exception as GeneralException;
use Saml\Ecp\Util\Options;
use Saml\Ecp\Authentication;
use Saml\Ecp\Discovery;
use Zend\Http;


class Client
{

    const OPT_PROTECTED_CONTENT_URI = 'protected_content_uri';

    const OPT_HTTP_CLIENT = 'http_client';

    const OPT_SOAP_ENVELOPE_XSD = 'soap_envelope_xsd';

    /**
     * The Zend HTTP client.
     * 
     * @var Http\Client
     */
    protected $_httpClient = null;

    /**
     * The request factory object.
     * 
     * @var Request\RequestFactoryInterface
     */
    protected $_requestFactory = null;

    /**
     * Options.
     * 
     * @var Options
     */
    protected $_options = null;


    /**
     * Constructor.
     * 
     * @param array|\Traversable $options
     */
    public function __construct ($options = array())
    {
        $this->setOptions($options);
    }


    /**
     * Sets the options.
     * 
     * @param array|\Traversable $options
     */
    public function setOptions ($options)
    {
        $this->_options = new Options($options);
    }


    /**
     * Returns the target URI protected by the SP.
     * 
     * @param boolean $throwException
     * @throws GeneralException\MissingConfigException
     * @return string
     */
    public function getProtectedContentUri ($throwException = false)
    {
        $uri = (string) $this->_options->get(self::OPT_PROTECTED_CONTENT_URI);
        if (! $uri && $throwException) {
            throw new GeneralException\MissingConfigException(self::OPT_PROTECTED_CONTENT_URI);
        }
        
        return $uri;
    }


    /**
     * Returns the http client.
     * 
     * @param boolean $throwException
     * @throws GeneralException\MissingDependencyException
     * @return Http\Client
     */
    public function getHttpClient ($throwException = false)
    {
        if (! ($this->_httpClient instanceof Http\Client)) {
            
            if ($throwException) {
                throw new GeneralException\MissingDependencyException('http client');
            }
            
            $httpClientConfig = $this->_options->get(self::OPT_HTTP_CLIENT);
            if (! $httpClientConfig || ! is_array($httpClientConfig)) {
                $httpClientConfig = array();
            }
            $this->_httpClient = $this->_createHttpClient($httpClientConfig);
        }
        
        return $this->_httpClient;
    }


    /**
     * Sets the HTTP client.
     * 
     * @param Http\Client $httpClient
     */
    public function setHttpClient (Http\Client $httpClient)
    {
        $this->_httpClient = $httpClient;
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


    public function getResponseValidatorFactory ()
    {}


    public function setResponseValidatorFactory ()
    {}


    /**
     * Performs the whole authentication flow. 
     * 
     * @param Authentication\Method\MethodInterface $authenticationMethod
     */
    public function authenticate (Authentication\Method\MethodInterface $authenticationMethod, 
        Discovery\Method\MethodInterface $discoveryMethod)
    {
        $requestFactory = $this->getRequestFactory();
        
        // send PAOS request to SP
        $spInitialRequest = $requestFactory->createSpInitialRequest();
        $spInitialResponse = $this->sendInitialRequestToSp($spInitialRequest);
        $spInitialResponse->validate();
        
        // send authn request to IdP
        $idpAuthnRequest = $requestFactory->createIdpAuthnRequest($spInitialResponse, $discoveryMethod->getIdpEcpEndpoint());
        $idpAuthnResponse = $this->sendAuthnRequestToIdp($idpAuthnRequest, $authenticationMethod);
        $idpAuthnResponse->validate();
        
        // convey the authn response back to the SP
        $spConveyRequest = $requestFactory->createSpAuthnConveyRequest($idpAuthnResponse, $idpAuthnResponse->getConsumerEndpointUrl());
        $spConveyResponse = $this->sendAuthnResponseToSp($spConveyRequest);
        $spConveyResponse->validate();
        
        // access protected resource
        $spResourceRequest = $requestFactory->createSpResourceRequest($this->getProtectedContentUri());
        $spResourceResponse = $this->sendResourceRequestToSp($spResourceRequest);
        
        return $spResourceResponse;
    }


    /**
     * Send the initial request to the SP.
     * 
     * This should be the first step in the authentication flow. The client tries to access a protected
     * location at the SP and expects a session initiation start.
     * 
     * @param RequestInterface $request
     * @return Response\ResponseInterface
     */
    public function sendInitialRequestToSp (Request\RequestInterface $request)
    {
        /* @var $request \Saml\Ecp\Request\SpInitialRequest */
        $request->setUri($this->getProtectedContentUri(true));
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        
        return new Response\SpInitialResponse($httpResponse);
    }


    /**
     * Send the processed authn request from the SP to IdP along with the user credentials.
     * 
     * The IdP should authenticate the user automatically and it should return an authn response.
     * 
     * @param Request\RequestInterface $request
     * @param Authentication\Method\MethodInterface $authenticationMethod
     * @return Response\ResponseInterface
     */
    public function sendAuthnRequestToIdp (Request\RequestInterface $request, 
        Authentication\Method\MethodInterface $authenticationMethod)
    {
        /* @var $request \Saml\Ecp\Request\IdpAuthnRequest */
        $client = $this->getHttpClient();
        $authenticationMethod->configureHttpClient($client);
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        $client->resetParameters();
        
        return new Response\IdpAuthnResponse($httpResponse);
    }


    /**
     * Conveys the processed authn response from the IdP to the SP.
     * 
     * @param Request\RequestInterface $request
     * @return Response\ResponseInterface
     */
    public function sendAuthnResponseToSp (Request\RequestInterface $request)
    {
        /* @var $request \Saml\Ecp\Request\SpConveyAuthnRequest */
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        
        return new Response\SpConveyAuthnResponse($httpResponse);
    }


    /**
     * After successful authentication sends a request for the protected resource to the SP.
     * 
     * @param Request\RequestInterface $request
     * @return Response\ResponseInterface
     */
    public function sendResourceRequestToSp (Request\RequestInterface $request)
    {
        /* @var $request \Saml\Ecp\Request\SpResourceRequest */
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        
        return new Response\SpResourceResponse($httpResponse);
    }
    
    /*
     * Protected/private
     */
    
    /**
     * Creates and returns the HTTP client based on the provided configuration.
     * 
     * @param array $config
     * @return Http\Client
     */
    protected function _createHttpClient (array $config)
    {
        $adapter = new Http\Client\Adapter\Socket();
        $client = new Http\Client();
        if (isset($config['options']) && is_array($config['options'])) {
            $client->setOptions($config['options']);
        }
        $client->setAdapter($adapter);
        
        if (isset($config['context']) && is_array($config['context'])) {
            $adapter->setStreamContext($config['context']);
        }
        
        return $client;
    }


    /**
     * Performs a HTTP request and returns the response.
     * 
     * @param Http\Request $request
     * @return Http\Response
     */
    protected function _sendHttpRequest (Http\Request $httpRequest)
    {
        try {
            $httpResponse = $this->getHttpClient()
                ->send($httpRequest);
        } catch (\Exception $e) {
            throw new Exception\HttpRequestException(sprintf("HTTP request exception: [%s] %s", get_class($e), $e->getMessage()));
        }
        
        return $httpResponse;
    }
}