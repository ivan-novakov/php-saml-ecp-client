<?php

namespace SamlTest\Ecp\Response\Validator;

use Saml\Ecp\Protocol\AuthnResponseInterface;
use Saml\Ecp\Response\Validator\PaosRequest;


class PaosRequestTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PaosRequest
     */
    protected $_validator = null;


    public function setUp ()
    {
        $this->_validator = new PaosRequest();
    }


    /**
     * @dataProvider dataProvider
     */
    public function testIsValidTrue ($service, $paosServiceUrl, $samlServiceUrl, $isValid, $msgCount)
    {
        $response = $this->_getResponseMock($service, $paosServiceUrl, $samlServiceUrl);
        
        $this->assertSame($isValid, $this->_validator->isValid($response));
        $this->assertCount($msgCount, $this->_validator->getMessages());
    }


    public function dataProvider ()
    {
        return array(
            array(
                'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp', 
                'url', 
                'url', 
                true, 
                0
            ), 
            array(
                'xxx', 
                'url', 
                'url', 
                false, 
                1
            ), 
            array(
                'urn:oasis:names:tc:SAML:2.0:profiles:SSO:ecp', 
                'url1', 
                'url2', 
                false, 
                1
            ), 
            array(
                'xxx', 
                'url1', 
                'url2', 
                false, 
                2
            )
        );
    }


    public function _getResponseMock ($service, $paosServiceUrl, $samlServiceUrl)
    {
        $soapMessage = $this->getMockBuilder('Saml\Ecp\Soap\Message\AuthnRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $soapMessage->expects($this->once())
            ->method('getPaosRequestService')
            ->will($this->returnValue($service));
        $soapMessage->expects($this->any())
            ->method('getPaosResponseConsumerUrl')
            ->will($this->returnValue($paosServiceUrl));
        $soapMessage->expects($this->any())
            ->method('getAssertionConsumerServiceUrl')
            ->will($this->returnValue($samlServiceUrl));
        
        $response = $this->getMock('Saml\Ecp\Response\ResponseInterface');
        $response->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapMessage));
        
        return $response;
    }
}