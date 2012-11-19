<?php

namespace Saml\Ecp\Client;

use Saml\Ecp\Response\Validator\ValidatorFactory;
use Saml\Ecp\Response;
use Saml\Ecp\Request;
use Saml\Ecp\Exception as GeneralException;
use Saml\Ecp\Util\Options;
use Saml\Ecp\Authentication;
use Saml\Ecp\Discovery;
use Zend\Http;
use Zend\Log;


/**
 * Main "bootstrap" class that brings everyhitng in the library together.
 *
 * "Magic" log calls caught in __call():
 * 
 * @method void emerg()    emerg(string $message)
 * @method void alert()    alert(string $message)
 * @method void crit()     crit(string $message)
 * @method void err()      err(string $message)
 * @method void warn()     warn(string $message)
 * @method void notice()   notice(string $message)
 * @method void info()     info(string $message)
 * @method void debug()    debug(string $message)
 */
class Client implements Log\LoggerAwareInterface
{

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
     * The response factory object.
     * 
     * @var Response\ResponseFactoryInterface
     */
    protected $_responseFactory = null;

    /**
     * The response validator factory.
     * 
     * @var Response\Validator\ValidatorFactoryInterface
     */
    protected $_responseValidatorFactory = null;

    /**
     * Logger.
     * 
     * @var Log\LoggerInterface
     */
    protected $_logger = null;

    /**
     * The client context object.
     * 
     * @var Context
     */
    protected $_context = null;

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
     * Returns all options.
     * 
     * @return Options
     */
    public function getOptions ()
    {
        return $this->_options;
    }


    /**
     * Returns the value of the required option.
     * 
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getOption ($name, $defaultValue = null)
    {
        return $this->_options->get($name, $defaultValue);
    }


    /**
     * Sets the logger.
     * 
     * @param Log\LoggerInterface $logger
     */
    public function setLogger (Log\LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }


    /**
     * Returns the logger.
     * 
     * @return Log\LoggerInterface
     */
    public function getLogger ($throwException = false)
    {
        if ($throwException && ! ($this->_logger instanceof Log\LoggerInterface)) {
            throw new GeneralException\MissingDependencyException('logger');
        }
        return $this->_logger;
    }


    /**
     * Sets the client context.
     * 
     * @param Context $context
     */
    public function setContext (Context $context)
    {
        $this->_context = $context;
    }


    /**
     * Returns the client context.
     * 
     * @return Context
     */
    public function getContext ()
    {
        if (! ($this->_context instanceof Context)) {
            $this->_context = new Context();
        }
        return $this->_context;
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
            
            $httpClientConfig = $this->getOption(self::OPT_HTTP_CLIENT);
            if (! is_array($httpClientConfig)) {
                throw new GeneralException\MissingConfigException('http_client');
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


    /**
     * Returns the response factory.
     * 
     * @return Response\ResponseFactoryInterface
     */
    public function getResponseFactory ()
    {
        if (! ($this->_responseFactory instanceof Response\ResponseFactoryInterface)) {
            $this->_responseFactory = new Response\ResponseFactory();
        }
        
        return $this->_responseFactory;
    }


    /**
     * Sets the response factory.
     * 
     * @param Response\ResponseFactoryInterface $responseFactory
     */
    public function setResponseFactory (Response\ResponseFactoryInterface $responseFactory)
    {
        $this->_responseFactory = $responseFactory;
    }


    /**
     * Returns the response validator factory.
     * 
     * @return Response\Validator\ValidatorFactoryInterface
     */
    public function getResponseValidatorFactory ()
    {
        if (! ($this->_responseValidatorFactory instanceof Response\Validator\ValidatorFactoryInterface)) {
            $this->_responseValidatorFactory = new Response\Validator\ValidatorFactory(array(
                Response\Validator\ValidatorFactory::OPT_SOAP_ENVELOPE_XSD => $this->getOption(self::OPT_SOAP_ENVELOPE_XSD)
            ));
            $this->_responseValidatorFactory->setClientContext($this->getContext());
        }
        
        return $this->_responseValidatorFactory;
    }


    /**
     * Sets the response validator factory.
     * 
     * @param Response\Validator\ValidatorFactoryInterface $responseValidatorFactory
     */
    public function setResponseValidatorFactory (Response\Validator\ValidatorFactoryInterface $responseValidatorFactory)
    {
        $this->_responseValidatorFactory = $responseValidatorFactory;
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
        $this->info($request);
        
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        
        $response = $this->getResponseFactory()
            ->createSpInitialResponse($httpResponse);
        $validator = $this->getResponseValidatorFactory()
            ->createSpInitialResponseValidator();
        
        $this->validateResponse($validator, $response, 'initial SP response');
        
        $this->getContext()
            ->setSpInitialResponse($response);
        
        $this->info($response);
        return $response;
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
        $this->info($request);
        
        $client = $this->getHttpClient();
        $authenticationMethod->configureHttpClient($client);
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        $client->resetParameters();
        
        $response = $this->getResponseFactory()
            ->createIdpAuthnResponse($httpResponse);
        $validator = $this->getResponseValidatorFactory()
            ->createIdpAuthnResponseValidator();
        
        $this->validateResponse($validator, $response, 'IdP authn response');
        
        $this->info($response);
        return $response;
    }


    /**
     * Conveys the processed authn response from the IdP to the SP.
     * 
     * @param Request\RequestInterface $request
     * @return Response\ResponseInterface
     */
    public function sendAuthnResponseToSp (Request\RequestInterface $request)
    {
        $this->info($request);
        
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        
        $response = $this->getResponseFactory()
            ->createSpConveryAuthnResponse($httpResponse);
        
        $this->info($response);
        return $response;
    }


    /**
     * After successful authentication sends a request for the protected resource to the SP.
     * 
     * @param Request\RequestInterface $request
     * @return Response\ResponseInterface
     */
    public function sendResourceRequestToSp (Request\RequestInterface $request)
    {
        $this->info($request);
        
        $httpResponse = $this->_sendHttpRequest($request->getHttpRequest());
        
        $response = $this->getResponseFactory()
            ->createSpResourceResponse($httpResponse);
        
        $this->info($response);
        
        $this->info('Identity info: ' . $response->getContent());
        return $response;
    }


    /**
     * Runs a validation with the provided validator on the provided response.
     *
     * @param Response\Validator\ValidatorInterface $validator
     * @param Response\ResponseInterface $response
     * @param string $responseLabel
     * @throws Exception\InvalidResponseException
     * @throws Exception\ResponseValidationException
     */
    public function validateResponse (Response\Validator\ValidatorInterface $validator, 
        Response\ResponseInterface $response, $responseLabel = 'response')
    {
        $valid = false;
        
        try {
            $valid = $validator->isValid($response);
        } catch (\Exception $e) {
            throw new Exception\ResponseValidationException(sprintf("Exception during %s validation: [%s] %s", $responseLabel, get_class($e), $e->getMessage()));
        }
        
        if (! $valid) {
            throw new Exception\InvalidResponseException(sprintf("Invalid %s: %s", $responseLabel, implode(', ', $validator->getMessages())));
        }
        
        return $valid;
    }


    /**
     * The __call() magic method.
     * 
     * Used to catch logging calls and route them to the logger.
     * 
     * @param string $method
     * @param array $args
     */
    public function __call ($method, array $args)
    {
        $logMethods = array(
            'emerg', 
            'alert', 
            'crit', 
            'err', 
            'warn', 
            'notice', 
            'info', 
            'debug'
        );
        
        if (in_array($method, $logMethods)) {
            if (isset($args[0])) {
                $message = $args[0];
            } else {
                $message = '';
            }
            return $this->_log($method, $message);
        }
        
        throw new \RuntimeException(sprintf("Undefined method '%s'", $method));
    }
    
    /*
     * Protected/private
     */
    
    /**
     * Logs a message with the provided method, if the logger is set.
     * 
     * @param string $method
     * @param string $message
     */
    protected function _log ($method, $message)
    {
        if ($logger = $this->getLogger()) {
            $logger->$method($message);
        }
    }


    /**
     * Creates and returns the HTTP client based on the provided configuration.
     * 
     * @param array $config
     * @return Http\Client
     */
    protected function _createHttpClient (array $config)
    {
        $httpClientFactory = new HttpClientFactory();
        $client = $httpClientFactory->createHttpClient($config);
        
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