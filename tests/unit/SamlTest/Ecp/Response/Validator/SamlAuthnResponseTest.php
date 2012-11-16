<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\SamlAuthnResponse;


class SamlAuthnResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SamlAuthnResponse
     */
    protected $_validator = null;


    public function setUp ()
    {
        $this->_validator = new SamlAuthnResponse();
    }


    public function testIsValidTrue ()
    {
        $response = $this->_getResponseMock('http://some.url.org/');
        
        $this->assertTrue($this->_validator->isValid($response));
    }


    public function testisValidNoServiceUrl ()
    {
        $response = $this->_getResponseMock(null);
        
        $this->assertFalse($this->_validator->isValid($response));
    }


    protected function _getResponseMock ($consumerUrl)
    {
        $soapMessage = $this->getMockBuilder('Saml\Ecp\Soap\Message\AuthnResponse')
            ->disableOriginalConstructor()
            ->getMock();
        $soapMessage->expects($this->once())
            ->method('getAssertionConsumerServiceUrl')
            ->will($this->returnValue($consumerUrl));
        
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapMessage));
        
        return $response;
    }
}