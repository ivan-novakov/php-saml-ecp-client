<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\SamlAuthnRequest;


class SamlAuthnRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SoapAuthnRequest
     */
    protected $_validator = null;


    public function setUp ()
    {
        $this->_validator = new SamlAuthnRequest();
    }


    public function testIsValidTrue ()
    {
        $response = $this->_getResponseMock();
        
        $this->assertTrue($this->_validator->isValid($response));
        $this->assertCount(0, $this->_validator->getMessages());
    }


    public function testIsValidFalseInvalidElementName ()
    {
        $this->_validator->setOptions(array(
            SamlAuthnRequest::OPT_ELEMENT_NAME => 'DifferentElement'
        ));
        
        $response = $this->_getResponseMock();
        
        $this->assertFalse($this->_validator->isValid($response));
        $this->assertCount(1, $this->_validator->getMessages());
    }
    
    public function testIsValidFalseInvalidElementNs ()
    {
        $this->_validator->setOptions(array(
            SamlAuthnRequest::OPT_ELEMENT_NS => 'DifferentNs'
        ));
    
        $response = $this->_getResponseMock();
        
        $this->assertFalse($this->_validator->isValid($response));
        $this->assertCount(1, $this->_validator->getMessages());
    }


    public function _getResponseMock ()
    {
        $soapMessage = $this->getMockBuilder('Saml\Ecp\Soap\Message')
            ->disableOriginalConstructor()
            ->getMock();
        $soapMessage->expects($this->once())
            ->method('getBodyElements')
            ->will($this->returnValue($this->_getBodyElementsNodeList()));
        
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapMessage));
        
        return $response;
    }


    protected function _getBodyElementsNodeList ()
    {
        $dom = new \DOMDocument();
        $dom->load(TESTS_FILES_DIR . 'soap/soap-saml-authn-request.xml');
        
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('S', 'http://schemas.xmlsoap.org/soap/envelope/');
        $elements = $xpath->query('/S:Envelope/S:Body/*');
        
        return $elements;
    }
}