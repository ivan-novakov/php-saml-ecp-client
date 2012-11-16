<?php

namespace SamlTest\Ecp\Flow;

use Saml\Ecp\Flow\Basic;


class BasicTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Basic
     */
    protected $_flow = null;


    public function setUp ()
    {
        $this->_flow = new Basic();
    }


    public function testGetClientWhenNotSet ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingDependencyException');
        $this->_flow->getClient();
    }


    public function testGetClientAfterBeingSet ()
    {
        $client = $this->_getClientMock();
        $this->_flow->setClient($client);
        $this->assertSame($client, $this->_flow->getClient());
    }


    public function testAuthenticate ()
    {
        $protectedContentUri = 'https://protected';
        $idpEcpEndpoint = 'https://idp.example.org/endpoint';
        $consumerEndpointUrl = 'https://idp.example.org/endpoint';
        
        $spInitialRequest = $this->getMock('Saml\Ecp\Request\RequestInterface');
        $idpAuthnRequest = $this->getMock('Saml\Ecp\Request\RequestInterface');
        $spConveyRequest = $this->getMock('Saml\Ecp\Request\RequestInterface');
        $spResourceRequest = $this->getMock('Saml\Ecp\Request\RequestInterface');
        
        $spInitialResponse = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $idpAuthnResponse = $this->getMockBuilder('Saml\Ecp\Response\IdpAuthnResponse')
            ->disableOriginalConstructor()
            ->getMock();
        $idpAuthnResponse->expects($this->once())
            ->method('getConsumerEndpointUrl')
            ->will($this->returnValue($consumerEndpointUrl));
        $spConveyResponse = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $spResourceResponse = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        
        $authenticationMethod = $this->getMock('Saml\Ecp\Authentication\Method\MethodInterface');
        
        $discoveryMethod = $this->getMock('Saml\Ecp\Discovery\Method\MethodInterface');
        $discoveryMethod->expects($this->once())
            ->method('getIdpEcpEndpoint')
            ->will($this->returnValue($idpEcpEndpoint));
        
        $requestFactory = $this->getMock('Saml\Ecp\Request\RequestFactoryInterface');
        $requestFactory->expects($this->once())
            ->method('createSpInitialRequest')
            ->with($protectedContentUri)
            ->will($this->returnValue($spInitialRequest));
        $requestFactory->expects($this->once())
            ->method('createIdpAuthnRequest')
            ->with($spInitialResponse, $idpEcpEndpoint)
            ->will($this->returnValue($idpAuthnRequest));
        $requestFactory->expects($this->once())
            ->method('createSpAuthnConveyRequest')
            ->with($idpAuthnResponse, $consumerEndpointUrl)
            ->will($this->returnValue($spConveyRequest));
        $requestFactory->expects($this->once())
            ->method('createSpResourceRequest')
            ->with($protectedContentUri)
            ->will($this->returnValue($spResourceRequest));
        
        $client = $this->_getClientMock();
        $client->expects($this->exactly(2))
            ->method('getProtectedContentUri')
            ->will($this->returnValue($protectedContentUri));
        $client->expects($this->once())
            ->method('sendInitialRequestToSp')
            ->with($spInitialRequest)
            ->will($this->returnValue($spInitialResponse));
        $client->expects($this->once())
            ->method('sendAuthnRequestToIdp')
            ->with($idpAuthnRequest, $authenticationMethod)
            ->will($this->returnValue($idpAuthnResponse));
        $client->expects($this->once())
            ->method('sendAuthnResponseToSp')
            ->with($spConveyRequest)
            ->will($this->returnValue($spConveyResponse));
        $client->expects($this->once())
            ->method('sendResourceRequestToSp')
            ->with($spResourceRequest)
            ->will($this->returnValue($spResourceResponse));
        $client->expects($this->once())
            ->method('getRequestFactory')
            ->will($this->returnValue($requestFactory));
        
        $this->_flow->setClient($client);
        $this->assertSame($spResourceResponse, $this->_flow->authenticate($authenticationMethod, $discoveryMethod));
    }


    /**
     * 
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getClientMock ()
    {
        $client = $this->getMock('Saml\Ecp\Client\Client');
        
        return $client;
    }
}