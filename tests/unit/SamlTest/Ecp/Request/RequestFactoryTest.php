<?php

namespace SamlTest\Ecp\Request;

use Saml\Ecp\Soap\Message;
use Saml\Ecp\Request\RequestFactory;


class RequestFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var RequestFactory
     */
    protected $_factory = null;


    public function setUp ()
    {
        $this->_factory = new RequestFactory();
    }


    public function testCreateSpInitialRequest ()
    {
        $this->assertInstanceOf('Saml\Ecp\Request\SpInitialRequest', $this->_factory->createSpInitialRequest());
    }


    public function testCreateIdpAuthnRequest ()
    {
        $soapRequest = new Message($this->_getSoapData());
        
        $soapContainer = $this->getMockBuilder('Saml\Ecp\Soap\ContainerInterface')
            ->getMock();
        $soapContainer->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapRequest));
        
        $this->assertInstanceOf('Saml\Ecp\Request\IdpAuthnRequest', $this->_factory->createIdpAuthnRequest($soapContainer, 'http://test'));
    }


    public function testCreateSpAuthnConveyRequest ()
    {
        $soapRequest = new Message($this->_getSoapData());
        
        $soapContainer = $this->getMockBuilder('Saml\Ecp\Soap\ContainerInterface')
            ->getMock();
        $soapContainer->expects($this->once())
            ->method('getSoapMessage')
            ->will($this->returnValue($soapRequest));
        
        $this->assertInstanceOf('Saml\Ecp\Request\SpConveyAuthnRequest', $this->_factory->createSpAuthnConveyRequest($soapContainer, 'http://test'));
    }


    protected function _getSoapData ()
    {
        return file_get_contents(TESTS_FILES_DIR . 'soap-saml-authn-request.xml');
    }
}