<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\ContentType;


class ContentTypeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ContentType
     */
    protected $_validator = null;


    public function setUp ()
    {
        $this->_validator = new ContentType(array(
            ContentType::OPT_EXPECTED_CONTENT_TYPE => 'application/test'
        ));
    }


    public function testIsValidImplicit ()
    {
        $this->setExpectedException('Saml\Ecp\Exception\MissingOptionException');
        
        $validator = new ContentType();
        $validator->isValid($this->_getResponseMock('', true));
    }


    public function testIsValidTrue ()
    {
        $this->assertTrue($this->_validator->isValid($this->_getResponseMock('application/test')));
    }


    public function testIsValidFalse ()
    {
        $this->assertFalse($this->_validator->isValid($this->_getResponseMock('application/different')));
    }


    public function testIsValidTrueWithPartialComparison ()
    {
        $this->_validator->setOptions(array(
            ContentType::OPT_EXPECTED_CONTENT_TYPE => 'application/test', 
            ContentType::OPT_PARTIAL => true
        ));
        
        $this->assertTrue($this->_validator->isValid($this->_getResponseMock('application/test;muahaha')));
    }
    
    public function testIsValidFalseWithoutPartialComparison ()
    {
        $this->_validator->setOptions(array(
            ContentType::OPT_EXPECTED_CONTENT_TYPE => 'application/test'
        ));
    
        $this->assertFalse($this->_validator->isValid($this->_getResponseMock('application/test;muahaha')));
    }


    public function _getResponseMock ($contentType = '', $simple = false)
    {
        $response = $this->getMockBuilder('Saml\Ecp\Response\ResponseInterface')
            ->getMock();
        if ($simple) {
            return $response;
        }
        
        $header = $this->getMockBuilder('Zend\Http\Header\HeaderInterface')
            ->getMock();
        $header->expects($this->once())
            ->method('getFieldValue')
            ->will($this->returnValue($contentType));
        
        $headers = $this->getMockBuilder('Zend\Http\Headers')
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->with('Content-Type')
            ->will($this->returnValue($header));
        
        $httpClient = $this->getMockBuilder('Zend\Http\Response')
            ->getMock();
        $httpClient->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        
        $response->expects($this->once())
            ->method('getHttpResponse')
            ->will($this->returnValue($httpClient));
        
        return $response;
    }
}