<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\HttpStatus;


class HttpStatusTest extends \PHPUnit_Framework_TestCase
{


    public function testIsValidImplicitOk ()
    {
        $validator = new HttpStatus();
        $response = $this->_getResponseMock(200);
        $this->assertTrue($validator->isValid($response));
    }


    public function testIsValidImplicitBadStatus ()
    {
        $validator = new HttpStatus();
        $response = $this->_getResponseMock(400);
        $this->assertFalse($validator->isValid($response));
    }


    public function testIsValidSpecificCodeOk ()
    {
        $validator = new HttpStatus(array(
            HttpStatus::OPT_EXPECTED_STATUS => 301
        ));
        $response = $this->_getResponseMock(301);
        $this->assertTrue($validator->isValid($response));
    }


    public function testIsValidSpecificCodeBadStatus ()
    {
        $validator = new HttpStatus(array(
            HttpStatus::OPT_EXPECTED_STATUS => 301
        ));
        $response = $this->_getResponseMock(200);
        $this->assertFalse($validator->isValid($response));
    }


    protected function _getResponseMock ($returnCode)
    {
        $httpClient = $this->getMockBuilder('Zend\Http\Response')
            ->getMock();
        $httpClient->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue($returnCode));
        
        $response = $this->getMockBuilder('Saml\Ecp\Response\ResponseInterface')
            ->getMock();
        $response->expects($this->once())
            ->method('getHttpResponse')
            ->will($this->returnValue($httpClient));
        
        return $response;
    }
}