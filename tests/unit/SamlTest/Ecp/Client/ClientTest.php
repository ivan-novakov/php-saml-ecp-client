<?php

namespace SamlTest\Ecp\Client;

use Saml\Ecp\Client\Client;


class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Client
     */
    protected $_client = null;


    public function setUp ()
    {
        $this->_client = new Client();
    }


    public function testGetLoggerWhichIsNotSet ()
    {
        $this->assertNull($this->_client->getLogger());
    }


    public function testGetLoggerWhichIsNotSetThrowException ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingDependencyException');
        $this->_client->getLogger(true);
    }


    public function testGetLoggerAfterBeingSet ()
    {
        $logger = $this->getMock('Zend\Log\LoggerInterface');
        $this->_client->setLogger($logger);
        $this->assertSame($logger, $this->_client->getLogger());
    }


    public function testGetContextImplicit ()
    {
        $this->assertInstanceOf('Saml\Ecp\Client\Context', $this->_client->getContext());
    }


    public function testGetContextAfterBeingSet ()
    {
        $context = $this->_getContextMock();
        $this->_client->setContext($context);
        $this->assertSame($context, $this->_client->getContext());
    }


    public function testGetHttpClientWhichIsNotSet ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingConfigException');
        $this->assertInstanceOf('Zend\Http\Client', $this->_client->getHttpClient());
    }


    public function testGetHttpClientWhichIsNotSetWithThrowException ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingDependencyException');
        $this->_client->getHttpClient(true);
    }


    public function testGetHttpClientFromConfig ()
    {
        $this->_client->setOptions(array(
            Client::OPT_HTTP_CLIENT => array(
                'options' => array(
                    'cafile' => 'dummy'
                )
            )
        ));
        
        $this->assertInstanceOf('Zend\Http\Client', $this->_client->getHttpClient());
    }


    public function testGetHttpClientAfterBeingSet ()
    {
        $httpClient = new \Zend\Http\Client();
        $this->_client->setHttpClient($httpClient);
        
        $this->assertSame($httpClient, $this->_client->getHttpClient());
    }


    public function testGetRequestFactoryImplicit ()
    {
        $this->assertInstanceOf('Saml\Ecp\Request\RequestFactoryInterface', $this->_client->getRequestFactory());
    }


    public function testGetRequestFactoryAfterBeingSet ()
    {
        $requestFactory = $this->getMock('Saml\Ecp\Request\RequestFactoryInterface');
        $this->_client->setRequestFactory($requestFactory);
        $this->assertSame($requestFactory, $this->_client->getRequestFactory());
    }


    public function testGetResponseFactoryImplicit ()
    {
        $this->assertInstanceOf('Saml\Ecp\Response\ResponseFactoryInterface', $this->_client->getResponseFactory());
    }


    public function testGetResponseFactoryAfterBeingSet ()
    {
        $responseFactory = $this->getMock('Saml\Ecp\Response\ResponseFactoryInterface');
        $this->_client->setResponseFactory($responseFactory);
        $this->assertSame($responseFactory, $this->_client->getResponseFactory());
    }


    public function testGetResponseValidatorFactoryImplicit ()
    {
        $this->assertInstanceOf('Saml\Ecp\Response\Validator\ValidatorFactoryInterface', $this->_client->getResponseValidatorFactory());
    }


    public function testGetResponseValidatorFactoryAfterBeingSet ()
    {
        $validatorFactory = $this->getMock('Saml\Ecp\Response\Validator\ValidatorFactoryInterface');
        $this->_client->setResponseValidatorFactory($validatorFactory);
        $this->assertSame($validatorFactory, $this->_client->getResponseValidatorFactory());
    }
    
    //authenticate()
    //...
    public function testSendInitialRequestToSp ()
    {
        $httpRequest = $this->_getHttpRequestMock();
        $httpResponse = $this->_getHttpResponseMock();
        
        $request = $this->_getRequestMock($httpRequest);
        $response = $this->_getResponseMock();
        
        $context = $this->_getContextMock();
        $context->expects($this->once())
            ->method('setSpInitialResponse')
            ->with($response);
        $this->_client->setContext($context);
        
        $httpClient = $this->_getHttpClientMock($httpRequest, $httpResponse);
        $this->_client->setHttpClient($httpClient);
        
        $responseFactory = $this->_getResponseFactoryMock('createSpInitialResponse', $httpResponse, $response);
        $this->_client->setResponseFactory($responseFactory);
        
        $validator = $this->_getValidatorMock(true);
        
        $responseValidatorFactory = $this->_getResponseValidatorFactoryMock('createSpInitialResponseValidator', $validator);
        $this->_client->setResponseValidatorFactory($responseValidatorFactory);
        
        $this->assertSame($response, $this->_client->sendInitialRequestToSp($request));
    }


    public function testSendAuthnRequestToIdp ()
    {
        $httpRequest = $this->_getHttpRequestMock();
        $httpResponse = $this->_getHttpResponseMock();
        
        $request = $this->_getRequestMock($httpRequest);
        $response = $this->_getResponseMock();
        
        $httpClient = $this->_getHttpClientMock($httpRequest, $httpResponse);
        $httpClient->expects($this->once())
            ->method('resetParameters');
        $this->_client->setHttpClient($httpClient);
        
        $responseFactory = $this->_getResponseFactoryMock('createIdpAuthnResponse', $httpResponse, $response);
        $this->_client->setResponseFactory($responseFactory);
        
        $validator = $this->_getValidatorMock(true);
        
        $responseValidatorFactory = $this->_getResponseValidatorFactoryMock('createIdpAuthnResponseValidator', $validator);
        $this->_client->setResponseValidatorFactory($responseValidatorFactory);
        
        $authenticationMethod = $this->getMock('Saml\Ecp\Authentication\Method\MethodInterface');
        $authenticationMethod->expects($this->once())
            ->method('configureHttpClient')
            ->with($httpClient);
        
        $this->assertSame($response, $this->_client->sendAuthnRequestToIdp($request, $authenticationMethod));
    }


    public function testSendAuthnResponseToSp ()
    {
        $httpRequest = $this->_getHttpRequestMock();
        $httpResponse = $this->_getHttpResponseMock();
        
        $request = $this->_getRequestMock($httpRequest);
        $response = $this->_getResponseMock();
        
        $httpClient = $this->_getHttpClientMock($httpRequest, $httpResponse);
        $this->_client->setHttpClient($httpClient);
        
        $responseFactory = $this->_getResponseFactoryMock('createSpConveryAuthnResponse', $httpResponse, $response);
        $this->_client->setResponseFactory($responseFactory);
        
        $this->assertSame($response, $this->_client->sendAuthnResponseToSp($request));
    }


    public function testSendResourceRequestToSp ()
    {
        $httpRequest = $this->_getHttpRequestMock();
        $httpResponse = $this->_getHttpResponseMock();
        
        $request = $this->_getRequestMock($httpRequest);
        $response = $this->_getResponseMock();
        
        $httpClient = $this->_getHttpClientMock($httpRequest, $httpResponse);
        $this->_client->setHttpClient($httpClient);
        
        $responseFactory = $this->_getResponseFactoryMock('createSpResourceResponse', $httpResponse, $response);
        $this->_client->setResponseFactory($responseFactory);
        
        $this->assertSame($response, $this->_client->sendResourceRequestToSp($request));
    }


    public function testValidateResponseFalse ()
    {
        $this->setExpectedException('Saml\Ecp\Client\Exception\InvalidResponseException');
        
        $response = $this->_getResponseMock();
        $validator = $this->_getValidatorMock(false);
        
        $this->assertFalse($this->_client->validateResponse($validator, $response));
    }


    public function testValidateResponseOk ()
    {
        $response = $this->_getResponseMock();
        $validator = $this->_getValidatorMock(true);
        
        $this->assertTrue($this->_client->validateResponse($validator, $response));
    }


    public function testValidateResponseThrowsException ()
    {
        $this->setExpectedException('Saml\Ecp\Client\Exception\ResponseValidationException');
        
        $response = $this->_getResponseMock();
        $validator = $this->_getValidatorMock(false);
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->throwException(new \Exception()));
        
        $this->assertFalse($this->_client->validateResponse($validator, $response));
    }
    
    /*
     * ------------------------------------------------------------------------------------------------------------
     */
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getResponseFactoryMock ($factoryCall, $httpResponse, $response)
    {
        $responseFactory = $this->getMock('Saml\Ecp\Response\ResponseFactory');
        $responseFactory->expects($this->once())
            ->method($factoryCall)
            ->with($httpResponse)
            ->will($this->returnValue($response));
        
        return $responseFactory;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getResponseValidatorFactoryMock ($factoryCall, $validator)
    {
        $responseValidatorFactory = $this->getMock('Saml\Ecp\Response\Validator\ValidatorFactoryInterface');
        $responseValidatorFactory->expects($this->once())
            ->method($factoryCall)
            ->will($this->returnValue($validator));
        
        return $responseValidatorFactory;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getValidatorMock ($isValid = true)
    {
        $validator = $this->getMock('Saml\Ecp\Response\Validator\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue($isValid));
        $validator->expects($this->any())
            ->method('getMessages')
            ->will($this->returnValue(array()));
        
        return $validator;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getRequestMock ($httpRequest)
    {
        $request = $this->getMock('Saml\Ecp\Request\RequestInterface');
        $request->expects($this->once())
            ->method('getHttpRequest')
            ->will($this->returnValue($httpRequest));
        
        return $request;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getResponseMock ()
    {
        return $this->getMock('Saml\Ecp\Response\ResponseInterface');
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getContextMock ()
    {
        return $this->getMock('Saml\Ecp\Client\Context');
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getHttpClientMock ($httpRequest, $httpResponse)
    {
        $httpClient = $this->getMock('Zend\Http\Client');
        $httpClient->expects($this->once())
            ->method('send')
            ->with($httpRequest)
            ->will($this->returnValue($httpResponse));
        
        return $httpClient;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getHttpRequestMock ()
    {
        return $this->getMock('Zend\Http\Request');
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getHttpResponseMock ()
    {
        return $this->getMock('Zend\Http\Response');
    }
}