<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\SoapHeaderActor;


class SoapHeaderActorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SoapHeaderActor
     */
    protected $_validator = null;


    public function setUp ()
    {
        $this->_validator = new SoapHeaderActor();
    }


    public function testIsValidTrue ()
    {
        $response = $this->_getResponseMock();
        
        $this->assertTrue($this->_validator->isValid($response));
        $this->assertCount(0, $this->_validator->getMessages());
    }


    public function testIsValidFalse ()
    {
        $response = $this->_getResponseMock();
        $this->_validator->setRequiredAttributeValues(array(
            'actor' => 'different value', 
            'mustUnderstand' => '1'
        ));
        
        $this->assertFalse($this->_validator->isValid($response));
        $this->assertCount(3, $this->_validator->getMessages());
    }


    public function _getResponseMock ()
    {
        $soapMessage = $this->getMockBuilder('Saml\Ecp\Soap\Message')
            ->disableOriginalConstructor()
            ->getMock();
        $soapMessage->expects($this->once())
            ->method('getHeaderElements')
            ->will($this->returnValue($this->_getHeaderElementsNodeList()));
        
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapMessage));
        
        return $response;
    }


    protected function _getHeaderElementsNodeList ()
    {
        $dom = new \DOMDocument();
        $dom->load(TESTS_FILES_DIR . 'soap-saml-authn-request.xml');
        
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('S', 'http://schemas.xmlsoap.org/soap/envelope/');
        $elements = $xpath->query('/S:Envelope/S:Header/*');
        
        return $elements;
    }
}