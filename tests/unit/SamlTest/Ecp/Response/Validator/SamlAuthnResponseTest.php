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


    public function testIsValidNoSpServiceUrlSet ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingOptionException');
        $response = $this->_getResponseMock('https://some.url/');
        
        $this->_validator->isValid($response);
    }


    public function testIsValidNoServiceUrlInResponse ()
    {
        $this->_validator->setOptions(array(
            SamlAuthnResponse::OPT_SP_ASSERTION_CONSUMER_URL => 'https://some.url'
        ));
        $response = $this->_getResponseMock(null);
        
        $this->assertFalse($this->_validator->isValid($response));
    }


    public function testIsValidDifferentUrl ()
    {
        $this->_validator->setOptions(array(
            SamlAuthnResponse::OPT_SP_ASSERTION_CONSUMER_URL => 'https://some.url'
        ));
        $response = $this->_getResponseMock('https://different.url');
        
        $this->assertFalse($this->_validator->isValid($response));
    }


    public function testIsValidSameUrl ()
    {
        $url = 'https://some.url';
        $this->_validator->setOptions(array(
            SamlAuthnResponse::OPT_SP_ASSERTION_CONSUMER_URL => $url
        ));
        $response = $this->_getResponseMock($url);
        
        $this->assertTrue($this->_validator->isValid($response));
    }


    protected function _getResponseMock ($consumerUrl)
    {
        $soapMessage = $this->getMockBuilder('Saml\Ecp\Soap\Message\AuthnResponse')
            ->disableOriginalConstructor()
            ->getMock();
        $soapMessage->expects($this->any())
            ->method('getAssertionConsumerServiceUrl')
            ->will($this->returnValue($consumerUrl));
        
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapMessage));
        
        return $response;
    }
}