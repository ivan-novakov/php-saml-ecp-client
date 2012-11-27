<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Response\Validator\SoapResponse;


class SoapResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SoapResponse
     */
    protected $_validator = null;


    public function setUp ()
    {
        $this->_validator = new SoapResponse();
    }


    public function testIsValidWithFault ()
    {
        $this->assertFalse($this->_validator->isValid($this->_getResponseMock(true)));
    }


    public function testIsValidWithoutFault ()
    {
        $this->assertTrue($this->_validator->isValid($this->_getResponseMock(false)));
    }


    protected function _getResponseMock ($isFault = false)
    {
        $soapMessage = $this->getMockBuilder('Saml\Ecp\Soap\Message\Message')
            ->disableOriginalConstructor()
            ->getMock();
        $soapMessage->expects($this->once())
            ->method('isFault')
            ->will($this->returnValue($isFault));
        
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapMessage));
        
        return $response;
    }
}